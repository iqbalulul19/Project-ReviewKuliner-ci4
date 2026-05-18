<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $placeModel = new \App\Models\PlaceModel();
        $photoModel = new \App\Models\PlacePhotoModel();
        $reviewModel = new \App\Models\ReviewModel();

        $keyword = $this->request->getGet('keyword');

        if ($keyword) {
            // Jauh lebih pintar: Cari di nama tempat, alamat, ATAU gabungkan dengan nama kategori
            $places = $placeModel->select('places.*, categories.name as category_name')
                                 ->join('categories', 'categories.id = places.category_id', 'left')
                                 ->groupStart()
                                     ->like('places.name', $keyword)
                                     ->orLike('places.address', $keyword)
                                     ->orLike('categories.name', $keyword) // 👈 Kunci utamanya di sini!
                                 ->groupEnd()
                                 ->findAll();
        } else {
            $places = $placeModel->findAll();
        }

        // Looping pencarian foto cover (Biarkan tetap seperti bawaan milikmu)
        foreach ($places as $key => $place) {
            $cover = null;
            $path = '';

            $fotoAdmin = $photoModel->where('place_id', $place['id'])->first();
            if ($fotoAdmin) {
                $cover = $fotoAdmin['photo'];
                $path = 'uploads/';
            } else {
                $fotoReview = $reviewModel->where('place_id', $place['id'])
                                          ->where('photo IS NOT NULL')
                                          ->where('photo !=', '')
                                          ->first();
                if ($fotoReview) {
                    $cover = $fotoReview['photo'];
                    $path = 'uploads/reviews/';
                }
            }

            $places[$key]['cover_image'] = $cover;
            $places[$key]['cover_path'] = $path;
        }

        $data = [
            'places'  => $places,
            'keyword' => $keyword
        ];

        return view('home', $data); 
    }
}