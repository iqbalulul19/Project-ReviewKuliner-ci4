<?php

namespace App\Controllers;

use App\Models\PlaceModel; // Panggil modelnya di sini

class Home extends BaseController
{
    public function index()
    {
        $placeModel = new PlaceModel();

        // Ambil semua data kuliner dari database
        $data['places'] = $placeModel->findAll();

        // Kirim data tersebut ke file 'home.php'
        return view('home', $data);
    }
}
