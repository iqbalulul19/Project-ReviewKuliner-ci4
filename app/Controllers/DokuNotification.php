<?php

namespace App\Controllers;

use App\Models\UserVoucherModel;

class DokuNotification extends BaseController
{
    public function index()
    {
        // 1. Ambil data JSON yang dikirim oleh server DOKU
        $rawPayload = file_get_contents('php://input');
        $payload = json_decode($rawPayload, true);

        // Jika tidak ada data, tolak request
        if (!$payload) {
            return $this->response->setStatusCode(400)->setBody('Invalid Payload');
        }

        // 2. Cek apakah status transaksi dari DOKU adalah SUCCESS
        if (isset($payload['transaction']['status']) && $payload['transaction']['status'] === 'SUCCESS') {
            
            // Ambil Invoice Number (Order ID) yang dikirim DOKU
            $invoiceNumber = $payload['order']['invoice_number'];

            $uvModel = new UserVoucherModel();
            
            // 3. Cari data transaksi di database berdasarkan Order ID
            $transaction = $uvModel->where('order_id', $invoiceNumber)->first();

            // 4. Jika transaksi ditemukan dan masih pending, ubah jadi paid
            if ($transaction && $transaction['status'] === 'pending') {
                $uvModel->update($transaction['id'], [
                    'status' => 'paid'
                ]);
            }
        }

        // 5. WAJIB: Berikan respon HTTP 200 OK ke DOKU
        // Jika tidak dijawab 200 OK, DOKU akan mengira aplikasimu mati dan terus mengirim ulang notifikasi.
        return $this->response->setStatusCode(200)->setBody('OK');
    }
}