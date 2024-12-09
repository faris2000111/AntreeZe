<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileSeeder extends Seeder
{
    public function run()
    {
        DB::table('profile')->insert([
            [
                'id' => 1,
                'nama_usaha' => 'AntreQu',
                'logo' => 'https://antreeze.kingperseus.online/storage/logos/icons.png',
                'warna' => '#2152FF',
                'jam_buka' => '08:00:00',
                'jam_tutup' => '17:00:00',
                'banner1' => 'https://antreeze.kingperseus.online/storage/banners/banner2.webp',
                'banner2' => 'https://antreeze.kingperseus.online/storage/banners/banner3.webp',
                'banner3' => 'https://antreeze.kingperseus.online/storage/banners/P3mMJCNTOeBh4vhNH0Dxf1Nr76bPao1bHcnCWOPg.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
