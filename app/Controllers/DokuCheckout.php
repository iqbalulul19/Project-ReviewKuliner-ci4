<?php

namespace App\Controllers;

use App\Models\VoucherModel;
use App\Models\UserVoucherModel;
use App\Models\PlaceModel;

class DokuCheckout extends BaseController
{
    public function confirm($id)
    {
        // 1. Panggil Model
        $voucherModel = new \App\Models\VoucherModel();
        $placeModel = new \App\Models\PlaceModel();

        // 2. Cari data voucher berdasarkan ID yang diklik
        $voucher = $voucherModel->find($id);

        // Jika voucher tidak ada/salah URL, kembalikan ke halaman utama
        if (!$voucher) {
            return redirect()->to('/')->with('error', 'Voucher tidak ditemukan.');
        }

        // 3. Cari data tempat kuliner berdasarkan place_id yang ada di tabel voucher
        $place = $placeModel->find($voucher['place_id']);

        // 4. Kirim kedua data tersebut ke View
        $data = [
            'voucher' => $voucher,
            'place'   => $place
        ];

        return view('checkout_confirm', $data);
    }

    // Fungsi proses menggunakan API langsung (CURL CI4)
    public function process($voucher_id)
    {
        $vModel = new VoucherModel();
        $voucher = $vModel->find($voucher_id);

        if (!$voucher) {
            return redirect()->back()->with('error', 'Voucher tidak ditemukan');
        }

        $invoiceNumber = 'INV-' . time();
        
        // 1. Simpan pesanan ke Database (Pending)
        $uvModel = new UserVoucherModel();
        $uvModel->save([
            'user_id'    => session()->get('user_id'),
            'voucher_id' => $voucher_id,
            'order_id'   => $invoiceNumber,
            'status'     => 'pending'
        ]);

        // 2. Siapkan Kredensial & URL DOKU Sandbox
        $clientId   = env('DOKU_CLIENT_ID');
        $sharedKey  = env('DOKU_SHARED_KEY');
        $targetPath = '/checkout/v1/payment';
        $url        = 'https://api-sandbox.doku.com' . $targetPath;
        $requestId  = time() . rand(100, 999); // ID Request unik
        $timestamp  = gmdate("Y-m-d\TH:i:s\Z"); // Waktu standar UTC

        // 3. Susun Data Pesanan
        $dataRequest = [
            'order' => [
                'amount'         => (int)$voucher['price'],
                'invoice_number' => $invoiceNumber,
                'currency'       => 'IDR',
                'callback_url' => 'https://antiquity-arose-fit.ngrok-free.dev/checkout/success/' . $invoiceNumber 
            ],
            'payment' => [
                'payment_due_date' => 60 // batas waktu pembayaran dalam 60 menit
            ],
            'customer' => [
                'name'  => session()->get('name') ?? 'Guest',
                'email' => session()->get('email') ?? 'iqbalulul19@gmail.com'
            ],
            'notify_url' => 'https://antiquity-arose-fit.ngrok-free.dev/doku/notification'
        ];

        // 4. Generate Signature Keamanan DOKU (Wajib)
        $bodyJson = json_encode($dataRequest);
        $digest   = base64_encode(hash('sha256', $bodyJson, true));
        
        $signatureString = "Client-Id:" . $clientId . "\n" .
                           "Request-Id:" . $requestId . "\n" .
                           "Request-Timestamp:" . $timestamp . "\n" .
                           "Request-Target:" . $targetPath . "\n" .
                           "Digest:" . $digest;
                           
        $signature = base64_encode(hash_hmac('sha256', $signatureString, $sharedKey, true));

        // 5. Tembak API Menggunakan CURL Bawaan CodeIgniter 4
        $client = \Config\Services::curlrequest();
        
        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Client-Id'         => $clientId,
                    'Request-Id'        => $requestId,
                    'Request-Timestamp' => $timestamp,
                    'Signature'         => "HMACSHA256=" . $signature,
                    'Content-Type'      => 'application/json'
                ],
                'body' => $bodyJson,
                'http_errors' => false 
            ]);

            // 6. Baca Balasan dari DOKU
            $result = json_decode($response->getBody(), true);
            
            if (isset($result['response']['payment']['url'])) {
                return redirect()->to($result['response']['payment']['url']);
            } else {
                return redirect()->back()->with('error', 'Gagal membuat halaman pembayaran. Cek log atau Client ID.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Koneksi ke DOKU gagal: ' . $e->getMessage());
        }
    }


// Tambahkan parameter $invoiceNumber di dalam kurung
public function success($order_id = null) 
{
    // Jika tidak ada order_id di URL, tendang ke halaman awal
    if (!$order_id) {
        return redirect()->to('/');
    }

    $uvModel = new \App\Models\UserVoucherModel();
    $voucherModel = new \App\Models\VoucherModel();
    $userModel = new \App\Models\UserModel(); 

    // 1. Cari transaksi yang SANGAT SPESIFIK berdasarkan order_id dari URL
    $transaction = $uvModel->where('order_id', $order_id)->first();

    if (!$transaction) {
        return redirect()->to('/');
    }

    if ($transaction['status'] === 'pending') {
        $uvModel->update($transaction['id'], [
            'status' => 'paid'
        ]);
        
        $transaction['status'] = 'paid';
    }

    // 2. Ambil detail voucher & data pembeli asli dari database
    $voucher = $voucherModel->find($transaction['voucher_id']);
    $pembeli = $userModel->find($transaction['user_id']); 

    // 3. Kirim data pasti ke View
    $data = [
        'customer_name' => $pembeli['name'] ?? 'Guest', 
        'order_id'      => $transaction['order_id'],
        'amount'        => $voucher['price'] ?? 0,
        'status'        => $transaction['status']
    ];
    
    return view('checkout_success', $data); 
}

    public function cancel()
    {
        $uvModel = new \App\Models\UserVoucherModel();
        $userId = session()->get('user_id');
        // 1. Cari transaksi terakhir milik user ini yang masih berstatus 'pending'
        $pendingTransaction = $uvModel->where('user_id', $userId)
                                      ->where('status', 'pending')
                                      ->orderBy('id', 'DESC')
                                      ->first();
        // 2. Jika ketemu, ubah statusnya menjadi 'cancelled' agar riwayatnya jelas
        if ($pendingTransaction) {
            $uvModel->update($pendingTransaction['id'], [
                'status' => 'cancelled' 
            ]);
        }
        // 3. Arahkan user kembali ke halaman utama dengan pesan pemberitahuan
        return redirect()->to('/')->with('error', 'Pembayaran telah dibatalkan.');
    }
}