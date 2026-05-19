<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUserAndReview extends Migration
{
    public function up()
    {
    // 1. Ubah tabel admins menjadi users dan tambah kolom 'role' & 'name'
    $this->forge->addColumn('users', [
        'name' => ['type' => 'VARCHAR', 'constraint' => 100, 'after' => 'id'],
        'role' => ['type' => 'ENUM', 'constraint' => ['admin', 'user'], 'default' => 'user', 'after' => 'password'],
    ]);

    // 2. Tambah kolom user_id di tabel reviews
    $this->forge->addColumn('reviews', [
        'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'after' => 'place_id'],
    ]);
    }

    public function down()
    {
        //
    }
}
