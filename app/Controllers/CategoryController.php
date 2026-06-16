<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    // 1. Menampilkan Halaman Kelola Kategori
    public function index()
    {
        // Gembok Keamanan: Tendang user biasa yang mencoba masuk
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Akses ditolak! Anda bukan admin.');
        }

        $data = [
            'title'      => 'Kelola Kategori | Admin',
            'categories' => $this->categoryModel->findAll()
        ];

        return view('admin/categories/index', $data);
    }

    // 2. Menyimpan Kategori Baru
// 2. Menyimpan Kategori Baru
    public function store()
    {
        if (session()->get('role') !== 'admin') return redirect()->to('/');

        // BLOK VALIDASI
        $rules = [
            'name' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Nama kategori tidak boleh kosong!']
            ]
        ];

        // Jika kosong, tendang balik dan kirimkan pesan error
        if (!$this->validate($rules)) {
            return redirect()->to('/admin/categories')
                             ->withInput()
                             ->with('errorValidation', $this->validator->getErrors());
        }

        // Jika aman, simpan ke database
        $this->categoryModel->save([
            'name' => $this->request->getPost('name')
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    // 3. Mengupdate Kategori
    public function update($id)
    {
        if (session()->get('role') !== 'admin') return redirect()->to('/');

        $this->categoryModel->update($id, [
            'name' => $this->request->getPost('name')
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Nama kategori berhasil diperbarui!');
    }

    // 4. Menghapus Kategori
    public function delete($id)
    {
        if (session()->get('role') !== 'admin') return redirect()->to('/');

        $this->categoryModel->delete($id);
        
        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil dihapus!');
    }
}