<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PlaceModel;

class PlaceApi extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $placeModel = new PlaceModel();
        
        $places = $placeModel->findAll();

        // Jika data kosong
        if (empty($places)) {
            return $this->respond([
                'status'  => 404,
                'message' => 'Data tempat kuliner tidak ditemukan',
                'data'    => []
            ], 404);
        }


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

    public function create()
    {
        $placeModel = new PlaceModel();

        $data = [
            'name'        => $this->request->getVar('name'),
            'category_id' => $this->request->getVar('category_id'),
            'address'     => $this->request->getVar('address'),
            'latitude'    => $this->request->getVar('latitude'),
            'longitude'   => $this->request->getVar('longitude'),
            'status'      => 'approved'
        ];

        // Validasi sederhana
        if (empty($data['name']) || empty($data['address'])) {
            return $this->respond([
                'status'  => 400,
                'message' => 'Gagal! Nama dan Alamat wajib diisi.'
            ], 400);
        }

        // Simpan ke database
        if ($placeModel->insert($data)) {
            return $this->respondCreated([
                'status'  => 201,
                'message' => 'Berhasil menambahkan data tempat kuliner baru via API',
                'data'    => $data
            ]);
        }

        return $this->respond([
            'status'  => 500,
            'message' => 'Gagal menyimpan data ke database.'
        ], 500);
    }

    public function delete($id = null)
    {
        $placeModel = new PlaceModel();

        $data = $placeModel->find($id);
        
        if ($data) {
            $placeModel->delete($id);
            return $this->respondDeleted([
                'status'  => 200,
                'message' => 'Data tempat kuliner dengan ID ' . $id . ' berhasil dihapus via API.'
            ]);
        }

        return $this->respond([
            'status'  => 404,
            'message' => 'Data tidak ditemukan. Hapus dibatalkan.'
        ], 404);
    }
}