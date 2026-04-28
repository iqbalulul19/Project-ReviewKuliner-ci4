<?php

namespace App\Controllers;

use App\Models\PlaceModel;
use App\Models\PlacePhotoModel;
class Home extends BaseController
{
    public function index()
    {
        $placeModel = new PlaceModel();
        $photoModel = new PlacePhotoModel();

        // Ambil semua data tempat kuliner
        $places = $placeModel->findAll();

        // Looping untuk mencari 1 foto pertama sebagai thumbnail tiap tempat
        foreach ($places as &$p) {
            $photo = $photoModel->where('place_id', $p['id'])->first();
            
            // Jika ada foto, simpan nama filenya. Jika tidak, kosongkan.
            $p['thumbnail'] = $photo ? $photo['photo'] : null;
        }

        $data = [
            'places' => $places
        ];

        return view('home', $data);
    }
}