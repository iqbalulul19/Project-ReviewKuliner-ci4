<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePlacePhotos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'place_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'photo'    => ['type' => 'VARCHAR', 'constraint' => '255'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('place_photos');
    }

    public function down()
    {
        $this->forge->dropTable('place_photos');
    }
}
