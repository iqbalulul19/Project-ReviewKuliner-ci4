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
            // Filter: Hanya tampilkan yang disetujui (approved) saja
            $places = $placeModel->select('places.*, categories.name as category_name')
                ->join('categories', 'categories.id = places.category_id', 'left')
                ->where('places.status', 'approved') // <-- Kunci perbaikannya di sini
                ->groupStart()
                ->like('places.name', $keyword)
                ->orLike('places.address', $keyword)
                ->orLike('categories.name', $keyword)
                ->groupEnd()
                ->findAll();
        } else {
            // Filter: Tampilkan semua asalkan statusnya disetujui
            $places = $placeModel->where('status', 'approved')->findAll(); // <-- Kunci perbaikannya di sini
        }

        // Looping pencarian foto cover (Biarkan tetap seperti bawaan)
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
