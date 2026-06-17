<?php

namespace App\Models;

use CodeIgniter\Model;

class TagModel extends Model
{
  protected $table            = 'tags';
  protected $primaryKey       = 'id';
  protected $allowedFields    = ['name'];
  protected $useTimestamps    = true;

  // Helper untuk mengambil tag yang dimiliki oleh suatu tempat
  public function getTagsByPlace($placeId)
  {
    return $this->db->table('place_tags')
      ->select('tags.*')
      ->join('tags', 'tags.id = place_tags.tag_id')
      ->where('place_tags.place_id', $placeId)
      ->get()
      ->getResultArray();
  }

  // Helper untuk menyinkronkan tag saat insert/update tempat
  public function syncPlaceTags($placeId, array $tagIds)
  {
    // Hapus relasi lama terlebih dahulu
    $this->db->table('place_tags')->where('place_id', $placeId)->delete();

    // Masukkan relasi baru jika ada tag yang dipilih
    if (!empty($tagIds)) {
      $data = [];
      foreach ($tagIds as $tagId) {
        $data[] = [
          'place_id' => $placeId,
          'tag_id'   => $tagId
        ];
      }
      $this->db->table('place_tags')->insertBatch($data);
    }
  }
}
