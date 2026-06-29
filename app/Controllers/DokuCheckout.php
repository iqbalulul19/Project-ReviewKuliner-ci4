<?php

namespace App\Controllers;

use App\Models\VoucherModel;
use App\Models\UserVoucherModel;
use App\Models\PlaceModel;

class DokuCheckout extends BaseController
{
    // Fungsi confirm tetap sama
    public function confirm($voucher_id) {
        $vModel = new VoucherModel();
        $voucher = $vModel->find($voucher_id);
        
        if (!$voucher) {
            return redirect()->back()->with('error', 'Voucher tidak ditemukan');
        }

        $pModel = new PlaceModel();
        $place = $pModel->find($voucher['place_id']);

        return view('checkout_confirm', [
            'voucher' => $voucher,
            'place'   => $place
        ]);
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
                // URL untuk mengembalikan user ke aplikasimu setelah bayar
                'callback_url'   => 'https://antiquity-arose-fit.ngrok-free.dev/checkout/success' 
            ],
            'payment' => [
                'payment_due_date' => 60 
            ],
            'customer' => [
                'name'  => session()->get('name') ?? 'Guest',
                'email' => session()->get('email') ?? 'guest@example.com'
            ],
            // Memaksa DOKU mengirim webhook ke Ngrok kita
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
                'http_errors' => false // Biarkan CI4 menangkap pesan error dari DOKU
            ]);

            // 6. Baca Balasan dari DOKU
            $result = json_decode($response->getBody(), true);
            
            // Jika sukses, DOKU akan memberikan URL Halaman Pembayaran
            if (isset($result['response']['payment']['url'])) {
                return redirect()->to($result['response']['payment']['url']);
            } else {
                return redirect()->back()->with('error', 'Gagal membuat halaman pembayaran. Cek log atau Client ID.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Koneksi ke DOKU gagal: ' . $e->getMessage());
        }
    }

   public function success()
{
    $uvModel = new \App\Models\UserVoucherModel();
    $vModel  = new \App\Models\VoucherModel();
    
    // 1. Ambil Nama User dari Session (Jika tidak ada, tampilkan 'Guest')
    $customerName = session()->get('name') ?? 'Guest';
    $userId = session()->get('user_id');

    $amount = 0;
    $orderId = '-';

    // 2. Cari transaksi TERAKHIR milik user ini
    $latestTransaction = $uvModel->where('user_id', $userId)
                                 ->orderBy('id', 'DESC')
                                 ->first();

    if ($latestTransaction) {
        $orderId = $latestTransaction['order_id'];
        
        // Ambil data harga voucher
        $voucher = $vModel->find($latestTransaction['voucher_id']);
        if ($voucher) {
            $amount = $voucher['price']; // Sesuaikan dengan nama kolom hargamu
        }

        if ($latestTransaction['status'] === 'pending') {
            $uvModel->update($latestTransaction['id'], [
                'status' => 'paid'
            ]);

            if ($voucher && isset($voucher['stock']) && $voucher['stock'] > 0) {
                $vModel->update($voucher['id'], [
                    'stock' => $voucher['stock'] - 1
                ]);
            }
        }
    }

    // 4. Bungkus data untuk dikirim ke View
    $data = [
        'customerName' => $customerName,
        'orderId'      => $orderId,
        'amount'       => $amount
    ];

    return view('checkout_success', $data);
}
}