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

    protected $allowedFields = [
        'user_id', 
        'voucher_id', 
        'order_id', 
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}