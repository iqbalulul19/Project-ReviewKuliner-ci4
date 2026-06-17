<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KulinerSeeder extends Seeder
{
    public function run()
    {
        // 1. DATA MASTER KATEGORI (ID Dikunci)
        $categories = [
            ['id' => 1, 'name' => 'Warteg & Nasi', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'Cafe & Kopi',  'created_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'name' => 'Street Food',  'created_at' => date('Y-m-d H:i:s')],
            ['id' => 4, 'name' => 'Mie & Bakso',  'created_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('categories')->insertBatch($categories);

        // 2. DATA MASTER TAG (ID Dikunci)
        $tags = [
            ['id' => 1, 'name' => 'Aesthetic',    'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'Murah Meriah', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'name' => 'Lesehan',      'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 4, 'name' => 'Outdoor',      'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 5, 'name' => 'Wifi Cepat',   'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 6, 'name' => 'Live Music',   'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 7, 'name' => 'Instagramable', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('tags')->insertBatch($tags);

        // 3. DATA 21 TEMPAT KULINER (ID wajib dikunci manual agar bisa direlasikan ke pivot)
        $places = [
            ['id' => 1,  'category_id' => 1, 'name' => 'Warteg Kharisma Bahari Nakula', 'address' => 'Jl. Nakula I No.10', 'latitude' => -6.982100, 'longitude' => 110.409100, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 2,  'category_id' => 1, 'name' => 'Nasi Ayam Bu Pini', 'address' => 'Jl. Pemuda', 'latitude' => -6.981500, 'longitude' => 110.410200, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 3,  'category_id' => 1, 'name' => 'Ayam Geprek Bensu Tugu Muda', 'address' => 'Sekitar Tugu Muda', 'latitude' => -6.983200, 'longitude' => 110.408500, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 4,  'category_id' => 1, 'name' => 'Penyetan Mas Kobis', 'address' => 'Jl. Sadewa', 'latitude' => -6.981800, 'longitude' => 110.407500, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 5,  'category_id' => 1, 'name' => 'Nasi Padang Murah Meriah', 'address' => 'Jl. Pendrikan Lor', 'latitude' => -6.980500, 'longitude' => 110.409800, 'created_at' => date('Y-m-d H:i:s')],

            ['id' => 6,  'category_id' => 2, 'name' => 'Kopi Janji Jiwa Udinus', 'address' => 'Gedung G Udinus', 'latitude' => -6.982500, 'longitude' => 110.409000, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 7,  'category_id' => 2, 'name' => 'Kenangan Mantan Cafe', 'address' => 'Jl. Imam Bonjol', 'latitude' => -6.980100, 'longitude' => 110.411000, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 8,  'category_id' => 2, 'name' => 'Angkringan Kopi Joss', 'address' => 'Pinggir Kali Garang', 'latitude' => -6.984000, 'longitude' => 110.407000, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 9,  'category_id' => 2, 'name' => 'Cafe Literasi Mahasiswa', 'address' => 'Jl. Arjuna', 'latitude' => -6.981200, 'longitude' => 110.408100, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 10, 'category_id' => 2, 'name' => 'Es Teh Indonesia', 'address' => 'Depan Kampus', 'latitude' => -6.982800, 'longitude' => 110.409500, 'created_at' => date('Y-m-d H:i:s')],

            ['id' => 11, 'category_id' => 3, 'name' => 'Telur Gulung SD Pendrikan', 'address' => 'Dekat SD Pendrikan', 'latitude' => -6.980800, 'longitude' => 110.408800, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 12, 'category_id' => 3, 'name' => 'Takoyaki Mas Bro', 'address' => 'Gerbang Utama Kampus', 'latitude' => -6.982200, 'longitude' => 110.409400, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 13, 'category_id' => 3, 'name' => 'Cilok Kuah Pedas Gila', 'address' => 'Jl. Nakula Raya', 'latitude' => -6.981900, 'longitude' => 110.408000, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 14, 'category_id' => 3, 'name' => 'Sosis Bakar Jumbo', 'address' => 'Area Parkir Motor', 'latitude' => -6.982600, 'longitude' => 110.409200, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 15, 'category_id' => 3, 'name' => 'Leker Paimo Cabang', 'address' => 'Jl. Karang Anyar', 'latitude' => -6.983500, 'longitude' => 110.410500, 'created_at' => date('Y-m-d H:i:s')],

            ['id' => 16, 'category_id' => 4, 'name' => 'Mie Ayam Bangka', 'address' => 'Jl. Hasanudin', 'latitude' => -6.979000, 'longitude' => 110.412000, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 17, 'category_id' => 4, 'name' => 'Bakso Sapi Urat Joss', 'address' => 'Perempatan Nakula', 'latitude' => -6.981000, 'longitude' => 110.409900, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 18, 'category_id' => 4, 'name' => 'Mie Gacoan', 'address' => 'Jl. Pamularsih', 'latitude' => -6.985000, 'longitude' => 110.405000, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 19, 'category_id' => 4, 'name' => 'Soto Ayam Bangkong', 'address' => 'Jl. MT Haryono', 'latitude' => -6.986000, 'longitude' => 110.415000, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 20, 'category_id' => 4, 'name' => 'Bakmi Jowo Pak Gareng', 'address' => 'Jl. Wotgandul', 'latitude' => -6.980000, 'longitude' => 110.413000, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 21, 'category_id' => 4, 'name' => 'Mie Jebew Super Pedas', 'address' => 'Kantin Kampus', 'latitude' => -6.982400, 'longitude' => 110.409300, 'created_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('places')->insertBatch($places);

        // ==================== [PROSES MENYUNTIKKAN DATA TAG KE DATA PLACE] ====================
        $place_tags = [
            // Warteg Kharisma (ID: 1) -> diberi label Murah Meriah (ID: 2)
            ['place_id' => 1, 'tag_id' => 2],

            // Penyetan Mas Kobis (ID: 4) -> diberi label Murah Meriah (ID: 2) & Lesehan (ID: 3)
            ['place_id' => 4, 'tag_id' => 2],
            ['place_id' => 4, 'tag_id' => 3],

            // Kopi Janji Jiwa (ID: 6) -> diberi label Aesthetic (ID: 1) & Wifi Cepat (ID: 5)
            ['place_id' => 6, 'tag_id' => 1],
            ['place_id' => 6, 'tag_id' => 5],

            // Kenangan Mantan Cafe (ID: 7) -> diberi label Aesthetic (ID: 1) & Live Music (ID: 6)
            ['place_id' => 7, 'tag_id' => 1],
            ['place_id' => 7, 'tag_id' => 6],

            // Angkringan Kopi Joss (ID: 8) -> diberi label Lesehan (ID: 3) & Outdoor (ID: 4)
            ['place_id' => 8, 'tag_id' => 3],
            ['place_id' => 8, 'tag_id' => 4],

            // Mie Gacoan (ID: 18) -> diberi label Murah Meriah (ID: 2) & Wifi Cepat (ID: 5)
            ['place_id' => 18, 'tag_id' => 2],
            ['place_id' => 18, 'tag_id' => 5],
        ];
        $this->db->table('place_tags')->insertBatch($place_tags);
    }
}
