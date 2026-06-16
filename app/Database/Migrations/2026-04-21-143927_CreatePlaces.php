<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePlaces extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'category_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => '255'],
            'description' => ['type' => 'TEXT', 'null' => true],
            'address'     => ['type' => 'TEXT'],
            'latitude'    => ['type' => 'DECIMAL', 'constraint' => '10,8'],
            'longitude'   => ['type' => 'DECIMAL', 'constraint' => '11,8'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('places');
    }

    public function down()
    {
        $this->forge->dropTable('places');
    }
}
