<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTagsAndPlaceTags extends Migration
{
    public function up()
    {
        // 1. Tabel Master Tags
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tags');

        // 2. Tabel Pivot Place Tags (Relasi Many-to-Many)
        $this->forge->addField([
            'place_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tag_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ]);
        $this->forge->addKey(['place_id', 'tag_id']);
        $this->forge->addForeignKey('place_id', 'places', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tag_id', 'tags', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('place_tags');
    }

    public function down()
    {
        $this->forge->dropTable('place_tags');
        $this->forge->dropTable('tags');
    }
}
