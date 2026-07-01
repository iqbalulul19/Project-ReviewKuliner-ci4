<?php

namespace App\Controllers;

use App\Models\PlaceModel;
use App\Models\PlacePhotoModel;
use App\Models\ReviewModel;
use App\Models\VoucherModel;

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

    // Fungsi untuk nembak API Nominatim (Webservice Client) + Error Handling + Cache
    public function searchNominatim()
    {
        $address = $this->request->getPost('address');

        // 1. ERROR HANDLING: Validasi jika input alamat kosong
        if (empty(trim($address))) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Alamat tidak boleh kosong.'
            ]);
        }

        $cache = \Config\Services::cache();
        $cacheKey = 'nominatim_' . md5(strtolower(trim($address)));

        // Cek apakah data koordinat alamat ini sudah pernah dicari sebelumnya
        if ($cachedData = $cache->get($cacheKey)) {
            // Jika ada di cache, langsung kembalikan datanya (Lebih cepat tanpa perlu hit API lagi!)
            return $this->response->setContentType('application/json')->setBody($cachedData);
        }

        $url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($address) . "&format=json";

        // Inisialisasi cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'KulinerApp_Mahasiswa/1.0'); 

        // 2. ERROR HANDLING: Batas waktu maksimal koneksi (Timeout) 10 detik
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $result = curl_exec($ch);

        // Ambil info status HTTP dan pesan error cURL
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // 3. ERROR HANDLING: Jika gagal terkoneksi (misal internet mati atau timeout)
        if ($result === false) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Gagal terhubung ke server peta: ' . $curlError
            ]);
        }

        // 4. ERROR HANDLING: Jika API Nominatim bermasalah (misal server mereka down)
        if ($httpcode !== 200) {
            return $this->response->setStatusCode($httpcode)->setJSON([
                'status'  => 'error',
                'message' => 'Layanan peta sedang tidak tersedia (Error ' . $httpcode . ')'
            ]);
        }

        // 5. ERROR HANDLING: Cek apakah hasil pencarian kosong (alamat tidak ditemukan)
        $dataArray = json_decode($result, true);
        if (empty($dataArray)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Koordinat untuk alamat tersebut tidak ditemukan di peta.'
            ]);
        }

        // Jika sukses dan alamat ditemukan, simpan hasilnya ke Cache selama 24 jam (86400 detik)
        $cache->save($cacheKey, $result, 86400);

        // Kembalikan response sukses
        return $this->response->setContentType('application/json')->setBody($result);
    }

    public function detail($id)
    {
        $placeModel = new \App\Models\PlaceModel();
        $photoModel = new \App\Models\PlacePhotoModel();
        $reviewModel = new \App\Models\ReviewModel();
        $voucherModel = new \App\Models\VoucherModel();
        $tagModel = new \App\Models\TagModel(); 
        
        // 1. TAMBAHKAN PEMANGGILAN MODEL TRANSAKSI
        $uvModel = new \App\Models\UserVoucherModel(); 
    
        // 1. Ambil data tempat
        $place = $placeModel->find($id);
    
        if (!$place) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Tempat kuliner tidak ditemukan!");
        }
    
        // 2. Ambil data pendukung
        $photos = $photoModel->where('place_id', $id)->findAll();
        $reviews = $reviewModel->select('reviews.*, users.name')
                ->join('users', 'users.id = reviews.user_id')
                ->where('place_id', $id)
                ->orderBy('reviews.created_at', 'DESC')
                ->findAll();
        
        // Ambil rata-rata rating
        $avgData = $reviewModel->selectAvg('rating')->where('place_id', $id)->first();
        $avgRating = $avgData['rating'] ? number_format($avgData['rating'], 1) : 0;
    
        // Ambil voucher
        $vouchers = $voucherModel->getActiveVouchers($id);
    
        // 2. TAMBAHKAN LOGIKA PERHITUNGAN SISA VOUCHER DI SINI
        foreach ($vouchers as &$v) {
            // Hitung berapa voucher ini yang statusnya sudah 'paid'
            $terjual = $uvModel->where('voucher_id', $v['id'])
                               ->where('status', 'paid')
                               ->countAllResults();
                               
            // Buat key 'sisa' baru ke dalam array voucher
            $v['sisa'] = $v['stock'] - $terjual; 
        }
    
        // Ambil tag
        $tags = []; 
    
        // 3. Gabungkan ke dalam satu array $data
        $data = [
            'place'      => $place,
            'photos'     => $photos,
            'reviews'    => $reviews,
            'tags'       => $tags,
            'avg_rating' => $avgRating,
            'vouchers'   => $vouchers
        ];
    
        return view('detail_place', $data);
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

        // Cek role user yang sedang login. Jika admin langsung approved, jika user biasa status pending
        $status = (session()->get('role') === 'admin') ? 'approved' : 'pending';

        // Simpan data teks & koordinat ke tabel places
        $placeModel->insert([
            'category_id' => $this->request->getPost('category_id'),
            'name'        => $this->request->getPost('name'),
            'address'     => $this->request->getPost('address'),
            'latitude'    => $this->request->getPost('latitude'),
            'longitude'   => $this->request->getPost('longitude'),
            'status'      => $status
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

        // Custom flashdata message berdasarkan status approval
        $message = ($status === 'pending')
            ? 'Tempat kuliner berhasil diajukan! Menunggu validasi dan persetujuan dari Admin.'
            : 'Tempat kuliner berhasil ditambahkan beserta tag!';

        return redirect()->to('/')->with('success', $message);
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

        return redirect()->to('/admin/places')->with('success', 'Tempat kuliner berhasil dihapus.');
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
        $placeModel = new PlaceModel();

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

        return redirect()->to('/tempat/' . $id)->with('success', 'Data tempat berhasil diperbarui.');
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

    public function places()
    {
        // Mengambil semua data dari Model tempat kuliner
        $placeModel = new \App\Models\PlaceModel();

        $data = [
            'title'  => 'Kelola Tempat Kuliner',
            'places' => $placeModel->findAll() // Mengambil semua data tempat
        ];

        // Memanggil file view yang ada di folder Views/admin/places.php
        return view('admin/places', $data);
    }

    // Menampilkan halaman antrean validasi tempat bagi Admin
    public function validations()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Akses ditolak! Menu khusus Admin.');
        }

        $placeModel = new \App\Models\PlaceModel();

        $data = [
            'title'  => 'Validasi Tempat Kuliner',
            'places' => $placeModel->where('status', 'pending')->findAll()
        ];

        return view('admin/validasi_tempat', $data);
    }

    // Menyetujui tempat kuliner (mengubah status ke approved)
    public function approvePlace($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Akses ditolak!');
        }

        $placeModel = new \App\Models\PlaceModel();
        $placeModel->update($id, ['status' => 'approved']);

        return redirect()->back()->with('success', 'Tempat kuliner berhasil disetujui dan dipublikasikan.');
    }

    // Menolak tempat kuliner (menghapus pengajuan)
    public function rejectPlace($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Akses ditolak!');
        }

        $placeModel = new \App\Models\PlaceModel();
        $placeModel->delete($id);

        return redirect()->back()->with('success', 'Pengajuan tempat kuliner ditolak dan dihapus.');
    }
}
