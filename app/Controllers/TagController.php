<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TagController extends BaseController
{
  protected $db;

  public function __construct()
  {
    // Memanggil database builder langsung tanpa model terpisah
    $this->db = \Config\Database::connect();
  }

  // 1. TAMPILKAN HALAMAN UTAMA TAG
  public function index()
  {
    $data['tags'] = $this->db->table('tags')->get()->getResultArray();

    // Memanggil view master data tag (Sesuaikan dengan file view tag Anda)
    return view('admin/tags/index', $data);
  }

  // 2. SIMPAN TAG BARU
  public function store()
  {
    $name = $this->request->getPost('name');

    if (!empty($name)) {
      $this->db->table('tags')->insert([
        'name'       => $name,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      return redirect()->to('/admin/tags')->with('success', 'Tag baru berhasil ditambahkan!');
    }

    return redirect()->to('/admin/tags')->with('error', 'Nama tag tidak boleh kosong.');
  }

  // 3. UPDATE/UBAH DATA TAG
  public function update($id)
  {
    $name = $this->request->getPost('name');

    if (!empty($name)) {
      $this->db->table('tags')->where('id', $id)->update([
        'name'       => $name,
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      return redirect()->to('/admin/tags')->with('success', 'Tag berhasil diperbarui!');
    }

    return redirect()->to('/admin/tags')->with('error', 'Nama tag tidak boleh kosong.');
  }

  // 4. HAPUS DATA TAG
  public function delete($id)
  {
    // Hapus data relasi di tabel pivot place_tags terlebih dahulu agar tidak memicu foreign key constraint error
    $this->db->table('place_tags')->where('tag_id', $id)->delete();

    // Hapus data tag utama
    $this->db->table('tags')->where('id', $id)->delete();

    return redirect()->to('/admin/tags')->with('success', 'Tag berhasil dihapus!');
  }
}
