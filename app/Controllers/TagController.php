<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TagController extends BaseController
{
  protected $db;

  public function __construct()
  {
    $this->db = \Config\Database::connect();
  }

  // 1. TAMPILKAN HALAMAN UTAMA TAG
  public function index()
  {
    $data['tags'] = $this->db->table('tags')->get()->getResultArray();
    return view('admin/tags/index', $data);
  }

  // 2. SIMPAN TAG BARU
  public function store()
  {
    $rules = [
      'name' => [
        'rules'  => 'required|is_unique[tags.name]',
        'errors' => [
          'required'  => 'Nama tag wajib diisi!',
          'is_unique' => 'Nama tag ini sudah terdaftar, silakan gunakan nama lain!'
        ]
      ]
    ];

    if (!$this->validate($rules)) {
      // Menggunakan redirect()->to dan mengubah nama flashdata menjadi 'tag_errors'
      return redirect()->to('/admin/tags')->withInput()->with('tag_errors', $this->validator->getErrors())->with('modal_action', 'tambah');
    }

    $this->db->table('tags')->insert([
      'name'       => $this->request->getPost('name'),
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ]);

    return redirect()->to('/admin/tags')->with('success', 'Tag baru berhasil ditambahkan!');
  }

  // 3. UPDATE/UBAH DATA TAG
  public function update($id)
  {
    $rules = [
      'name' => [
        'rules'  => "required|is_unique[tags.name,id,{$id}]",
        'errors' => [
          'required'  => 'Nama tag wajib diisi!',
          'is_unique' => 'Nama tag ini sudah terdaftar, silakan gunakan nama lain!'
        ]
      ]
    ];

    if (!$this->validate($rules)) {
      return redirect()->to('/admin/tags')->withInput()->with('tag_errors', $this->validator->getErrors())->with('modal_action', 'edit')->with('error_id', $id);
    }

    $this->db->table('tags')->where('id', $id)->update([
      'name'       => $this->request->getPost('name'),
      'updated_at' => date('Y-m-d H:i:s')
    ]);

    return redirect()->to('/admin/tags')->with('success', 'Tag berhasil diperbarui!');
  }

  // 4. HAPUS DATA TAG
  public function delete($id)
  {
    $this->db->table('place_tags')->where('tag_id', $id)->delete();
    $this->db->table('tags')->where('id', $id)->delete();

    return redirect()->to('/admin/tags')->with('success', 'Tag berhasil dihapus!');
  }
}
