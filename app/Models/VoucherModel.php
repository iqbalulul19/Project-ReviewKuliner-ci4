<?php

namespace App\Models;

use CodeIgniter\Model;

class VoucherModel extends Model
{
    protected $table            = 'vouchers';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $allowedFields    = ['place_id', 'title', 'description', 'price', 'discount_value', 'stock', 'expired_at'];

    public function getActiveVouchers($place_id)
    {
        return $this->where('place_id', $place_id)
                    ->where('stock >', 0)
                    ->where('expired_at >=', date('Y-m-d'))
                    ->findAll();
    }
}