<?php

namespace App\Models;

use CodeIgniter\Model;

class VoucherModel extends Model
{
    protected $table            = 'vouchers';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $allowedFields    = ['place_id', 'title', 'description', 'price', 'discount_value', 'stock', 'expired_at'];

<<<<<<< HEAD
=======
    // Ambil voucher yang masih aktif berdasarkan place_id
>>>>>>> 182126222d0ec10dd9f8946fc95789d2be08206a
    public function getActiveVouchers($place_id)
    {
        return $this->where('place_id', $place_id)
                    ->where('stock >', 0)
                    ->where('expired_at >=', date('Y-m-d'))
                    ->findAll();
    }
}