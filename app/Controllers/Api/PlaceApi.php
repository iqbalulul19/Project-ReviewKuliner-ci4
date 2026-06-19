<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PlaceModel;

class PlaceApi extends ResourceController
{
    // Menggunakan ResourceController bawaan CI4 untuk mempermudah pembuatan REST API
    protected $format = 'json'; // Format output otomatis JSON

    public function index()
    {
        $placeModel = new PlaceModel();
        
        // Ambil data tempat kuliner
        $places = $placeModel->findAll();

        // Jika data kosong
        if (empty($places)) {
            return $this->respond([
                'status'  => 404,
                'message' => 'Data tempat kuliner tidak ditemukan',
                'data'    => []
            ], 404);
        }

        // Jika data ada, kembalikan dalam format JSON yang rapi
        return $this->respond([
            'status'  => 200,
            'message' => 'Berhasil mengambil data tempat kuliner',
            'data'    => $places
        ], 200);
    }

    public function docs()
    {
        $docs = [
        'api_name'    => 'PetaKuliner API',
        'version'     => '1.0.0',
        'endpoints'   => [
            'GET /api/places' => [
                'description' => 'Mengambil daftar semua tempat kuliner',
                'auth'        => 'Header: X-API-KEY'
            ],
            'POST /api/places' => [
                'description' => 'Menambah data tempat kuliner baru',
                'auth'        => 'Header: X-API-KEY'
            ],
            'DELETE /api/places/(:id)' => [
                'description' => 'Menghapus tempat kuliner berdasarkan ID',
                'auth'        => 'Header: X-API-KEY'
            ]
        ],
        'note' => 'Pastikan menyertakan X-API-KEY pada setiap request.'
    ];

    return $this->respond($docs, 200);
    }
}