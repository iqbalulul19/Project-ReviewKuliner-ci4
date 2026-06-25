<?php

namespace App\Models;

use CodeIgniter\Model;

class FavoriteModel extends Model
{
  protected $table            = 'favorites';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $allowedFields    = ['user_id', 'place_id'];
  protected $useTimestamps    = true;

  public function getFavoritePlaces($userId)
  {
    return $this->select('places.*, favorites.id as favorite_id, 
                         COALESCE(
                             (SELECT photo FROM place_photos WHERE place_photos.place_id = places.id LIMIT 1),
                             (SELECT photo FROM reviews WHERE reviews.place_id = places.id AND photo IS NOT NULL AND photo != "" LIMIT 1)
                         ) as photo', false)
      ->join('places', 'places.id = favorites.place_id')
      ->where('favorites.user_id', $userId)
      ->findAll();
  }
}
