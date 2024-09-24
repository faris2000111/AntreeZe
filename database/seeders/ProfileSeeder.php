<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('profile')->insert([
            'id' => 1,
            'nama_usaha' => 'AntreQu',
            'logo' => 'logo-ct.png',
            'warna' => '#2152FF',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
