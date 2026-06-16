<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    
    // Kolom-kolom yang diizinkan untuk diisi data
    protected $allowedFields = ['name', 'username', 'password', 'role'];
}