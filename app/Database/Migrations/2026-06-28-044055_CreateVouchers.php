<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVouchers extends Migration
{
    public function up()
    {
        // 1. Tabel Vouchers (Master Data)
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'place_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'title'           => ['type' => 'VARCHAR', 'constraint' => 100], // Cth: Voucher Diskon Rp 20.000
            'description'     => ['type' => 'TEXT', 'null' => true], // Syarat & Ketentuan
            'price'           => ['type' => 'INT', 'constraint' => 11], // Harga beli voucher, misal: 10000
            'discount_value'  => ['type' => 'INT', 'constraint' => 11], // Nilai potongannya, misal: 20000
            'stock'           => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'expired_at'      => ['type' => 'DATE', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('place_id', 'places', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('vouchers');

        // 2. Tabel Transaksi User (User Vouchers)
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'voucher_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'order_id'        => ['type' => 'VARCHAR', 'constraint' => 50], // ID unik untuk Midtrans
            'status'          => ['type' => 'ENUM', 'constraint' => ['pending', 'paid', 'used', 'expired'], 'default' => 'pending'],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('voucher_id', 'vouchers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_vouchers');
    }

    public function down()
    {
        $this->forge->dropTable('user_vouchers');
        $this->forge->dropTable('vouchers');
    }
}