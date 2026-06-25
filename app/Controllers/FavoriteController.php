<?php

namespace App\Controllers;

use App\Models\FavoriteModel;

class FavoriteController extends BaseController
{
  protected $favoriteModel;

  public function __construct()
  {
    $this->favoriteModel = new FavoriteModel();
  }

  public function index()
  {
    if (!session()->get('logged_in')) {
      return redirect()->to('/login');
    }

    $userId = session()->get('user_id');

    $data = [
      'title'  => 'Favorit Saya',
      // Data $places sudah otomatis berisi foto berkat Model yang baru
      'places' => $this->favoriteModel->getFavoritePlaces($userId)
    ];

    return view('favorites', $data);
  }

  public function add($placeId)
  {
    if (!session()->get('logged_in')) {
      return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
    }

    $userId = session()->get('user_id');
    $existing = $this->favoriteModel->where(['user_id' => $userId, 'place_id' => $placeId])->first();

    if (!$existing) {
      $this->favoriteModel->insert([
        'user_id'  => $userId,
        'place_id' => $placeId
      ]);
      session()->setFlashdata('message', 'Tempat berhasil ditambahkan ke favorit.');
    }

    return redirect()->back();
  }

  public function delete($placeId)
  {
    if (!session()->get('logged_in')) {
      return redirect()->to('/login');
    }

    $userId = session()->get('user_id');
    $existing = $this->favoriteModel->where(['user_id' => $userId, 'place_id' => $placeId])->first();

    if ($existing) {
      $this->favoriteModel->delete($existing['id']);
      session()->setFlashdata('message', 'Tempat dihapus dari favorit.');
    }

    return redirect()->back();
  }
}
