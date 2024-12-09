<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin')->insert([
            [
                'id_admin' => 1,
                'nama_admin' => 'Mochamad Faris Hasan Febrianshah',
                'email' => 'farishasan099@gmail.com',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'no_hp' => '081234567890',
                'role' => 'admin',
                'avatar' => 'https://antreeze.kingperseus.online//storage/avatars/1731938527_logoapps.jpg',
                'remember_token' => Str::random(60),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_admin' => 11,
                'nama_admin' => 'Daffa',
                'email' => 'daffa@gmail.com',
                'username' => 'daffa',
                'password' => Hash::make('admin123'),
                'no_hp' => '087123123223',
                'role' => 'karyawan',
                'avatar' => 'https://api.dicebear.com/9.x/open-peeps/svg?seed=asdasd',
                'remember_token' => Str::random(60),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_admin' => 12,
                'nama_admin' => 'Putri',
                'email' => 'putri@gmail.com',
                'username' => 'putri rahayu',
                'password' => Hash::make('admin123'),
                'no_hp' => '081209857760',
                'role' => 'karyawan',
                'avatar' => 'https://api.dicebear.com/9.x/open-peeps/svg?seed=qweqwe',
                'remember_token' => Str::random(60),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
