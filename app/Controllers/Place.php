<?php

namespace App\Controllers;

use App\Models\PlaceModel;
use App\Models\PlacePhotoModel;
use App\Models\ReviewModel; 

class Place extends BaseController
{
    // Menampilkan halaman form tambah tempat
    public function create()
    {
        /*if (session()->get('role') !== 'admin' && session()->get('role') !== 'user') {
            return redirect()->to('/')->with('error', 'Akses ditolak! Hanya Admin dan User.');
        }*/
        return view('add_place');
    }

    /*public function searchNominatim()
    {
        // Ambil data alamat dari AJAX
        $address = $this->request->getPost('address');
        
        if (empty($address)) {
            return $this->response->setJSON([]);
        }

        // URL API Nominatim OpenStreetMap (Wajib di-urlencode agar spasi tidak merusak URL)
        $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($address) . "&limit=1";

        // KUNCI PERBAIKAN 2: Wajib set User-Agent bebas (identitas aplikasi) agar Nominatim mau merespon
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: PetaKulinerMahasiswa/1.0 (kontak-mahasiswa@example.com)\r\n"
            ]
        ];

        $context = stream_context_create($opts);
        $response = @file_get_contents($url, false, $context);

        // Jika gagal terhubung ke server OpenStreetMap
        if ($response === FALSE) {
            return $this->response->setJSON([]);
        }

        // Kembalikan hasilnya ke AJAX berupa JSON asli
        return $this->response->setJSON(json_decode($response));
    }*/

    // Fungsi untuk nembak API Nominatim
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
        $photoModel = new \App\Models\PlacePhotoModel();

        // Simpan data teks & koordinat ke tabel places
        $placeModel->insert([
            'category_id' => 1,
            'name'        => $this->request->getPost('name'),
            'address'     => $this->request->getPost('address'),
            'latitude'    => $this->request->getPost('latitude'),
            'longitude'   => $this->request->getPost('longitude'),
        ]);

        // Ambil ID tempat kuliner yang baru saja disimpan
        $placeId = $placeModel->insertID();

        // Proses Upload Foto
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

        $namaFoto = null;
        $fileFoto = $this->request->getFile('review_photo');
        if ($fileFoto && $fileFoto->isValid() && ! $fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'uploads/reviews', $namaFoto);
        }

        $reviewModel->insert([
            'place_id'   => $place_id,
            'user_id'    => session()->get('user_id'),
            'photo'      => $namaFoto,
            'rating'     => $this->request->getPost('rating'),
            'comment'    => $this->request->getPost('comment'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/tempat/' . $place_id);
    }

    // Menghapus Tempat Kuliner beserta foto dan ulasannya
    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Akses ditolak! Hanya Admin.');
        }

        $placeModel = new PlaceModel();
        $photoModel = new PlacePhotoModel();
        $reviewModel = new ReviewModel();

        // Cari dan hapus file foto fisik dari folder uploads
        $photos = $photoModel->where('place_id', $id)->findAll();
        foreach($photos as $foto) {
            $filePath = FCPATH . 'uploads/' . $foto['photo'];
            if(file_exists($filePath) && is_file($filePath)) {
                unlink($filePath); // Hapus file dari laptop
            }
        }

        // Hapus data dari database
        $photoModel->where('place_id', $id)->delete();  
        $reviewModel->where('place_id', $id)->delete();
        $placeModel->delete($id);                       

        return redirect()->to('/');
    }

    // Menampilkan form edit data
    public function edit($id)
    {
    $placeModel = new \App\Models\PlaceModel();
    $categoryModel = new \App\Models\CategoryModel(); 

    $data = [
        'title'      => 'Edit Tempat Kuliner',
        'place'      => $placeModel->find($id),
        'categories' => $categoryModel->findAll() 
    ];

    return view('edit_place', $data); // Sesuaikan dengan nama file view edit milikmu
    }

    // Memproses perubahan data ke database
    public function update($id)
    {
    $placeModel = new \App\Models\PlaceModel();

    // Ambil data post dan pastikan category_id ikut ditangkap
    $placeModel->update($id, [
        'name'        => $this->request->getPost('name'),
        'category_id' => $this->request->getPost('category_id'),
        'address'     => $this->request->getPost('address'),
        'latitude'    => $this->request->getPost('latitude'),
        'longitude'   => $this->request->getPost('longitude'),
    ]);

    return redirect()->to('/')->with('success', 'Data tempat kuliner berhasil diperbarui.');
    }

    public function deleteReview($id)
    {
        // 1. Keamanan Ekstra: Pastikan yang mengakses ini HANYA Admin
        if (session()->get('role') !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak! Hanya Admin yang boleh menghapus ulasan.');
        }

        $reviewModel = new \App\Models\ReviewModel();
        $review = $reviewModel->find($id);

        if ($review) {
            // 2. Hapus file foto dari folder server (Jika user tersebut melampirkan foto)
            if (!empty($review['photo']) && file_exists(FCPATH . 'uploads/reviews/' . $review['photo'])) {
                unlink(FCPATH . 'uploads/reviews/' . $review['photo']);
            }

            // 3. Hapus data ulasan dari database
            $reviewModel->delete($id);
            
            return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Ulasan tidak ditemukan.');
    }

    // 1. TAMPILKAN HALAMAN EDIT REVIEW
    public function editReview($id)
    {
        $reviewModel = new \App\Models\ReviewModel();
        $review = $reviewModel->find($id);

        // Kunci utamanya di sini: pakai 'user_id'
        if (!$review || $review['user_id'] != session()->get('user_id')) {
            return redirect()->to('/')->with('error', 'Akses ditolak! Anda hanya bisa mengedit ulasan Anda sendiri.');
        }

        $placeModel = new \App\Models\PlaceModel();
        $data = [
            'title'  => 'Edit Ulasan',
            'review' => $review,
            'place'  => $placeModel->find($review['place_id'])
        ];

        return view('edit_review', $data); 
    }

    // 2. PROSES UPDATE REVIEW KE DATABASE
    public function updateReview($id)
    {
        $reviewModel = new \App\Models\ReviewModel();
        $review = $reviewModel->find($id);

        // Di sini juga wajib pakai 'user_id'
        if (!$review || $review['user_id'] != session()->get('user_id')) {
            return redirect()->to('/')->with('error', 'Akses ditolak.');
        }

        $namaFoto = $review['photo'];
        $fileFoto = $this->request->getFile('review_photo');

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            if (!empty($review['photo']) && file_exists(FCPATH . 'uploads/reviews/' . $review['photo'])) {
                unlink(FCPATH . 'uploads/reviews/' . $review['photo']);
            }
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'uploads/reviews', $namaFoto);
        }

        $reviewModel->update($id, [
            'rating'  => $this->request->getPost('rating'),
            'comment' => $this->request->getPost('comment'),
            'photo'   => $namaFoto
        ]);

        return redirect()->to('/tempat/' . $review['place_id'])->with('success', 'Ulasan berhasil diperbarui.');
    }

    // 3. PROSES HAPUS REVIEW OLEH USER SENDIRI
    public function userDeleteReview($id)
    {
        $reviewModel = new \App\Models\ReviewModel();
        $review = $reviewModel->find($id);

        // Keamanan: Pastikan ulasan ini benar-earth milik user yang sedang login
        if (!$review || $review['user_id'] != session()->get('user_id')) {
            return redirect()->back()->with('error', 'Akses ditolak! Anda hanya bisa menghapus ulasan Anda sendiri.');
        }

        // Hapus foto fisik dari server
        if (!empty($review['photo']) && file_exists(FCPATH . 'uploads/reviews/' . $review['photo'])) {
            unlink(FCPATH . 'uploads/reviews/' . $review['photo']);
        }

        $reviewModel->delete($id);
        return redirect()->back()->with('success', 'Ulasan Anda berhasil dihapus.');
    }
}
