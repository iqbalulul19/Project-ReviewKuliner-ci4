<?php

namespace App\Controllers;
use App\Models\UserVoucherModel;

class DokuNotification extends BaseController
{
    public function index()
    {
        $rawPayload = file_get_contents('php://input');
        
        log_message('error', 'DOKU_DEBUG: Notifikasi diterima. Payload: ' . $rawPayload);

        $payload = json_decode($rawPayload, true);

        // Jika JSON tidak valid, hentikan
        if (!$payload) {
            log_message('error', 'DOKU_DEBUG: Payload kosong atau tidak valid.');
            return $this->response->setStatusCode(400)->setBody('Invalid Payload');
        }

        // Cek status SUCCESS
        if (isset($payload['transaction']['status']) && $payload['transaction']['status'] === 'SUCCESS') {
            $order_id = $payload['order']['invoice_number'];
            $uvModel = new UserVoucherModel();
            
            // Cari data di database
            $transaction = $uvModel->where('order_id', $order_id)->first();

            if ($transaction) {
                // Update status
                $uvModel->update($transaction['id'], ['status' => 'paid']);
                log_message('error', 'DOKU_DEBUG: Transaksi ' . $order_id . ' berhasil diupdate ke PAID.');
            } else {
                log_message('error', 'DOKU_DEBUG: Invoice ' . $order_id . ' TIDAK DITEMUKAN di database.');
            }
        }

        return $this->response->setStatusCode(200)->setBody('OK');
    }
}