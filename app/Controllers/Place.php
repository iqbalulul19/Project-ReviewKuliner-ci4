<?php

namespace App\Controllers;

use App\Models\PlaceModel;
use App\Models\PlacePhotoModel;
use App\Models\ReviewModel; // Tambahkan baris ini

class Place extends BaseController
{
    // Menampilkan halaman form tambah tempat
    public function create()
    {
        return view('add_place');
    }

    // Fungsi untuk nembak API Nominatim (Sesuai spesifikasi tugas D.3)
    public function searchNominatim()
    {
        $address = $this->request->getPost('address');
        $url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($address) . "&format=json";

        // Kita pakai cURL untuk kirim request ke Nominatim
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Nominatim mewajibkan kita ngasih tau siapa yang akses (User-Agent)
        curl_setopt($ch, CURLOPT_USERAGENT, 'KulinerApp_Mahasiswa/1.0');

        $result = curl_exec($ch);
        curl_close($ch);

        return $this->response->setContentType('application/json')->setBody($result);
    }

    public function store()
    {
        $placeModel = new PlaceModel();
        $photoModel = new \App\Models\PlacePhotoModel(); // Panggil model foto

        // 1. Simpan data teks & koordinat ke tabel places
        $placeModel->insert([
            'category_id' => 1,
            'name'        => $this->request->getPost('name'),
            'address'     => $this->request->getPost('address'),
            'latitude'    => $this->request->getPost('latitude'),
            'longitude'   => $this->request->getPost('longitude'),
        ]);

        // Ambil ID tempat kuliner yang baru saja disimpan
        $placeId = $placeModel->insertID();

        // 2. Proses Upload Foto (Resize dimatikan sementara)
        if ($imagefile = $this->request->getFiles()) {
            foreach ($imagefile['photos'] as $img) {
                if ($img->isValid() && ! $img->hasMoved()) {

                    // Bikin nama file acak biar gak bentrok
                    $newName = $img->getRandomName();

                    // Pindahkan file ke folder public/uploads/
                    $img->move(FCPATH . 'uploads', $newName);

                    // Panggil library Image CI4 untuk auto-resize maksimal 800px
                    // \Config\Services::image()
                    //     ->withFile(FCPATH . 'uploads/' . $newName)
                    //     ->resize(800, 800, true, 'auto') // true = jaga proporsi gambar
                    //     ->save(FCPATH . 'uploads/' . $newName);

                    // Simpan nama file ke database place_photos
                    $photoModel->insert([
                        'place_id' => $placeId,
                        'photo'    => $newName
                    ]);
                }
            }
        }

        // Kembali ke halaman utama
        return redirect()->to('/');
    }

    // Menampilkan halaman detail tempat kuliner beserta fotonya
    public function detail($id) {
    $placeModel = new PlaceModel();
    $reviewModel = new ReviewModel();
    
    $data['place'] = $placeModel->find($id);
    $data['photos'] = (new PlacePhotoModel())->where('place_id', $id)->findAll();

    // Ambil review beserta NAMA user-nya
    $data['reviews'] = $reviewModel->select('reviews.*, users.name')
                                   ->join('users', 'users.id = reviews.user_id')
                                   ->where('place_id', $id)
                                   ->orderBy('reviews.created_at', 'DESC')
                                   ->findAll();
    return view('detail_place', $data);
    }

    // Menyimpan data review & rating ke database
    public function storeReview()
    {
        $reviewModel = new \App\Models\ReviewModel();
        $place_id = $this->request->getPost('place_id');

        $reviewModel->insert([
            'place_id'   => $place_id,
            'user_id'    => session()->get('user_id'), // <-- Tangkap ID User yang sedang login
            'rating'     => $this->request->getPost('rating'),
            'comment'    => $this->request->getPost('comment'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/tempat/' . $place_id);
    }

    // Menghapus Tempat Kuliner beserta foto dan ulasannya
    public function delete($id)
    {
        $placeModel = new PlaceModel();
        $photoModel = new PlacePhotoModel();
        $reviewModel = new ReviewModel();

        // 1. Cari dan hapus file foto fisik dari folder uploads
        $photos = $photoModel->where('place_id', $id)->findAll();
        foreach($photos as $foto) {
            $filePath = FCPATH . 'uploads/' . $foto['photo'];
            if(file_exists($filePath) && is_file($filePath)) {
                unlink($filePath); // Hapus file dari laptop
            }
        }

        // 2. Hapus data dari database
        $photoModel->where('place_id', $id)->delete();  // Hapus data foto
        $reviewModel->where('place_id', $id)->delete(); // Hapus data ulasan
        $placeModel->delete($id);                       // Hapus data tempat utama

        // 3. Kembali ke halaman utama
        return redirect()->to('/');
    }

    // Menampilkan form edit data
    public function edit($id)
    {
        $placeModel = new PlaceModel();
        $data['place'] = $placeModel->find($id);
        
        if(empty($data['place'])) {
            return redirect()->to('/');
        }

        return view('edit_place', $data);
    }

    // Memproses perubahan data ke database
    public function update($id)
    {
        $placeModel = new PlaceModel();
        
        $placeModel->update($id, [
            'name'      => $this->request->getPost('name'),
            'address'   => $this->request->getPost('address'),
            'latitude'  => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
        ]);

        // Setelah berhasil diedit, kembalikan ke halaman detail
        return redirect()->to('/tempat/' . $id);
    }
}