<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ApiKeyFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $key = $request->getHeaderLine('X-API-KEY');
        
        $validKey = 'KULINER-API-2026'; 

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