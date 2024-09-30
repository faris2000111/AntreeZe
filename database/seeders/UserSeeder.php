<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id_users' => 1,
            'nama_pembeli' => 'karyawan',
            'username' => 'karyawan',
            'email' => 'farishasan012@gmail.com',
            'phone_number' => '08712312322',
            'password' => Hash::make('karyawan'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
