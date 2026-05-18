<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoryToPlaces extends Migration
{
    public function up()
    {
        // Menambahkan kolom category_id ke tabel places
        $fields = [
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Di-null agar data warung lama yang sudah ada tidak error
                'after'      => 'id'   // Meletakkan kolom ini tepat di bawah kolom ID
            ],
        ];
        $this->forge->addColumn('places', $fields);
    }

    public function down()
    {
        // Menghapus kembali kolom jika migration di-rollback
        $this->forge->dropColumn('places', 'category_id');
    }
}