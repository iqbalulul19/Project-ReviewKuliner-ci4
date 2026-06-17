<?php

namespace App\Models;

use CodeIgniter\Model;

class PlaceModel extends Model
{
    protected $table            = 'places';
    protected $primaryKey       = 'id';
   
    protected $allowedFields    = ['category_id', 'name', 'description', 'address', 'latitude', 'longitude', 'status'];
}
