<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'id_users' => 1,
                'nama_pembeli' => 'karyawan',
                'email' => 'farishasan012@gmail.com',
                'username' => 'karyawan',
                'password' => Hash::make('pembeli123'),
                'phone_number' => '08712312322',
                'phone_token' => NULL,
                'remember_token' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'device_token' => '',
                'verified_user' => 0,
                'avatar' => NULL,
            ],
            [
                'id_users' => 12,
                'nama_pembeli' => 'Akbar Haqi Al Muttaqim',
                'email' => 'akbarjoe814@gmail.com',
                'username' => 'akbarrr123',
                'password' => Hash::make('pembeli123'),
                'phone_number' => '086717717821',
                'phone_token' => NULL,
                'remember_token' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'device_token' => NULL,
                'verified_user' => 1,
                'avatar' => 'https://api.dicebear.com/9.x/avataaars-neutral/svg?seed=1728619441591&size=50',
            ],
            [
                'id_users' => 13,
                'nama_pembeli' => 'Daffa Aditya Rejasa Ruswanto',
                'email' => 'daffaaditya2911@gmail.com',
                'username' => 'daffaaditya28',
                'password' => Hash::make('pembeli123'),
                'phone_number' => '085851065295',
                'phone_token' => 'dnUKu-Y9R5eGynAw6K41LH:APA91bEUJKHcKyDKzoRkOliu_sZ4pFFEMJahyJVrKkLwJ6zZ0YRtWM6fGi-sxx0aUuu7ARfAUsK2IHmGUrOlTrH9ECoPHgKDpF--HOOB0yUs7KPuDtW-94g',
                'remember_token' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'device_token' => 'SKQ1.211006.001',
                'verified_user' => 1,
                'avatar' => 'https://api.dicebear.com/9.x/avataaars-neutral/svg?seed=1728636558138&size=50',
            ],
        ]);
    }
}
