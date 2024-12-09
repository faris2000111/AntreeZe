<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayananSeeder extends Seeder
{
    public function run()
    {
        DB::table('layanan')->insert([
            [
                'id_layanan' => 1,
                'nama_layanan' => 'Potong Rambut',
                'deskripsi' => 'Layanan potong rambut sesuai dengan gaya dan kebutuhan Anda, dirancang untuk memberikan tampilan yang segar dan menawan.',
                'gambar' => 'layanan1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_layanan' => 2,
                'nama_layanan' => 'Potong & Cuci Rambut',
                'deskripsi' => 'Paket layanan potong rambut sesuai dengan model pilihan pelanggan, lengkap dengan layanan cuci rambut untuk hasil yang segar dan rapi.',
                'gambar' => 'layanan2.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_layanan' => 3,
                'nama_layanan' => 'Warna Rambut',
                'deskripsi' => 'Kami menawarkan layanan pewarnaan rambut sesuai keinginan Anda, dengan pilihan warna beragam. Anda juga dapat memberikan referensi gambar untuk mendapatkan warna rambut yang sesuai harapan.',
                'gambar' => 'layanan3.webp',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
