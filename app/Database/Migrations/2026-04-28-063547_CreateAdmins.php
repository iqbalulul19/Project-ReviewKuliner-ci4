<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdmins extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            // ==================== TAMBAHKAN KOLOM NAME INI ====================
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            // ==================================================================
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            // ==================== TAMBAHKAN KOLOM ROLE INI ====================
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'user'],
                'default'    => 'user',
            ],
            // ==================================================================
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);

        // Sesuaikan nama tabel Anda di bawah ini (apakah 'users' atau 'admins')
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
