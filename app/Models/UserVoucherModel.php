<?php

namespace App\Models;

use CodeIgniter\Model;

class UserVoucherModel extends Model
{
    protected $table      = 'user_vouchers';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    // Kolom yang diizinkan untuk diisi/update oleh sistem
    protected $allowedFields = [
        'user_id', 
        'voucher_id', 
        'order_id', 
        'status' // Status akan berisi 'pending' atau 'paid'
    ];

    protected $useTimestamps = true; // Otomatis mengisi created_at dan updated_at
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}