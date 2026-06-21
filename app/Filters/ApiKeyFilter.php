<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ApiKeyFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Menangkap API Key yang dikirim melalui Header HTTP
        $key = $request->getHeaderLine('X-API-KEY');
        
        // Ini adalah kunci rahasia kita (Bisa diganti sesukamu nanti)
        $validKey = 'KULINER-API-2026'; 

        // Jika kunci kosong atau salah, tolak aksesnya! (401 Unauthorized)
        if (empty($key) || $key !== $validKey) {
            return \Config\Services::response()
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => 401,
                    'error'   => 'Akses ditolak! API Key tidak valid atau tidak dikirim.',
                    'message' => 'Harap sertakan X-API-KEY pada header request Anda.'
                ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu melakukan apa-apa setelah request selesai
    }
}