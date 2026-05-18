<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class Category extends BaseController
{
    // Keamanan: Pastikan hanya admin yang bisa masuk ke Controller ini
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        if (session()->get('role') !== 'admin') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Akses Ditolak");
        }
    }

    public function index()
    {
        $categoryModel = new CategoryModel();
        $data = [
            'title'      => 'Kelola Kategori',
            'categories' => $categoryModel->findAll()
        ];
        return view('admin/category_index', $data);
    }

    public function store()
    {
        $categoryModel = new CategoryModel();
        $name = $this->request->getPost('name');
        
        $categoryModel->save([
            'name' => $name,
            'slug' => url_title($name, '-', true) // Mengubah "Fast Food" menjadi "fast-food"
        ]);

        return redirect()->to('/admin/category')->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function delete($id)
    {
        $categoryModel = new CategoryModel();
        $categoryModel->delete($id);
        return redirect()->to('/admin/category')->with('success', 'Kategori berhasil dihapus.');
    }
}