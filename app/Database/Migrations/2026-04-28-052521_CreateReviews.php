<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReviews extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'place_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'rating'     => ['type' => 'INT', 'constraint' => 1], // Bintang 1 sampai 5
            'comment'    => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('reviews');
    }

    public function down()
    {
        $this->forge->dropTable('reviews');
    }
}