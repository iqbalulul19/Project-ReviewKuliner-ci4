<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username' => 'admin',
            // Enkripsi password menggunakan BCRYPT bawaan PHP
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
        ];
        $this->db->table('admins')->insert($data);
    }
}