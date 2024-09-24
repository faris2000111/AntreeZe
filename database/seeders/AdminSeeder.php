<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admin')->insert([
            'id_admin' => 1,
            'nama_admin' => 'Faris Hasan',
            'username' => 'admin',
            'role' => 'admin',
            'email' => 'farishasan099@gmail.com',
            'password' => Hash::make('admin'),
            'no_hp' => '081234567890',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
