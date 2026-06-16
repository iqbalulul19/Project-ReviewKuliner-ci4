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
        $tagModel = new \App\Models\TagModel();
        $categoryModel = new \App\Models\CategoryModel();

        $data = [
            'title'      => 'Tambah Tempat Kuliner',
            'categories' => $categoryModel->findAll(),
            'tags'       => $tagModel->findAll() // Kirim master tag ke view
        ];

        // Perbaikan: Pastikan data dilempar ke view agar checkbox muncul
        return view('add_place', $data);
    }

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
        // 1. BLOK VALIDASI: Cek apakah ada inputan yang kosong
        $rules = [
            'name' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Nama tempat kuliner wajib diisi!']
            ],
            'category_id' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Silakan pilih kategori terlebih dahulu!']
            ],
            'address' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Alamat lengkap tidak boleh kosong!']
            ],
            'latitude' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Koordinat wajib diisi! Klik tombol Cari Koordinat.']
            ]
        ];

        // Jika validasi gagal, kembalikan ke form beserta pesan error dan inputan sebelumnya
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. PROSES SIMPAN DATABASE (Jika validasi lolos)
        $placeModel = new PlaceModel();
        $photoModel = new \App\Models\PlacePhotoModel();

        // Simpan data teks & koordinat ke tabel places
        $placeModel->insert([
            'category_id' => $this->request->getPost('category_id'),
            'name'        => $this->request->getPost('name'),
            'address'     => $this->request->getPost('address'),
            'latitude'    => $this->request->getPost('latitude'),
            'longitude'   => $this->request->getPost('longitude'),
        ]);

        // Ambil ID tempat kuliner yang baru saja disimpan
        $placeId = $placeModel->insertID();

        // ==================== PROSES SIMPAN TAG ====================
        // Ambil data tag yang dipilih dari form (berupa array ID)
        $selectedTags = $this->request->getPost('tags') ?? [];

        // Sinkronisasikan ke tabel pivot via TagModel
        $tagModel = new \App\Models\TagModel();
        $tagModel->syncPlaceTags($placeId, $selectedTags);
        // ============================================================

        // Proses Upload Foto
        if ($imagefile = $this->request->getFiles()) {
            foreach ($imagefile['photos'] as $img) {
                if ($img->isValid() && ! $img->hasMoved()) {

                    // Bikin nama file acak biar gak bentrok
                    $newName = $img->getRandomName();

                    // Pindahkan file ke folder public/uploads/
                    $img->move(FCPATH . 'uploads', $newName);

                    // Simpan nama file ke database place_photos
                    $photoModel->insert([
                        'place_id' => $placeId,
                        'photo'    => $newName
                    ]);
                }
            }
        }

        return redirect()->to('/')->with('success', 'Tempat kuliner berhasil ditambahkan beserta tag!');
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
        foreach ($photos as $foto) {
            $filePath = FCPATH . 'uploads/' . $foto['photo'];
            if (file_exists($filePath) && is_file($filePath)) {
                unlink($filePath);
            }
        }

        // Hapus data relasi di tabel pivot master tag terlebih dahulu agar terhindar dari foreign key error
        $db = \Config\Database::connect();
        $db->table('place_tags')->where('place_id', $id)->delete();

        // Hapus data dari database utama
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
        $tagModel = new \App\Models\TagModel();

        // ==================== KUNCI AMAN FORM EDIT TAG ====================
        // Ambil data ID tag yang sudah berelasi dengan tempat kuliner ini dari tabel pivot
        $db = \Config\Database::connect();
        $currentTags = $db->table('place_tags')
            ->select('tag_id')
            ->where('place_id', $id)
            ->get()
            ->getResultArray();

        // Konversikan hasil array multi-dimensi menjadi array satu dimensi (contoh: [1, 3, 5])
        $mappedTags = array_column($currentTags, 'tag_id');
        // ==================================================================

        $data = [
            'title'      => 'Edit Tempat Kuliner',
            'place'      => $placeModel->find($id),
            'categories' => $categoryModel->findAll(),
            'tags'       => $tagModel->findAll(),
            'currentTags' => $mappedTags // Kirimkan data tag aktif ini ke file view edit_place.php
        ];

        return view('edit_place', $data);
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

        // ==================== UPDATE RELASI TAG SAAT EDIT ====================
        $selectedTags = $this->request->getPost('tags') ?? [];
        $tagModel = new \App\Models\TagModel();
        $tagModel->syncPlaceTags($id, $selectedTags);
        // =====================================================================

        return redirect()->to('/')->with('success', 'Data tempat kuliner berhasil diperbarui.');
    }

    public function deleteReview($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak! Hanya Admin yang boleh menghapus ulasan.');
        }

        $reviewModel = new \App\Models\ReviewModel();
        $review = $reviewModel->find($id);

        if ($review) {
            // Hapus file foto dari folder server
            if (!empty($review['photo']) && file_exists(FCPATH . 'uploads/reviews/' . $review['photo'])) {
                unlink(FCPATH . 'uploads/reviews/' . $review['photo']);
            }

            // Hapus data ulasan dari database
            $reviewModel->delete($id);

            return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Ulasan tidak ditemukan.');
    }

    // TAMPILKAN HALAMAN EDIT REVIEW
    public function editReview($id)
    {
        $reviewModel = new \App\Models\ReviewModel();
        $review = $reviewModel->find($id);

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

    // PROSES UPDATE REVIEW KE DATABASE
    public function updateReview($id)
    {
        $reviewModel = new \App\Models\ReviewModel();
        $review = $reviewModel->find($id);

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

    // PROSES HAPUS REVIEW OLEH USER SENDIRI
    public function userDeleteReview($id)
    {
        $reviewModel = new \App\Models\ReviewModel();
        $review = $reviewModel->find($id);

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
