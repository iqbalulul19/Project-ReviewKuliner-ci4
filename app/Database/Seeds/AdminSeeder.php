<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'       => 'Admin',
                'username'   => 'admin',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'role'       => 'admin',
            ],
            [
                'name'       => 'Iqbal Ulul',
                'username'   => 'iqbal',
                'password'   => password_hash('123', PASSWORD_DEFAULT),
                'role'       => 'user',
            ]
        ];

        // Memasukkan akun admin ke tabel users
        $this->db->table('users')->insertBatch($data);
    }
}