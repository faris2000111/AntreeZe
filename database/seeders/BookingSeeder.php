<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('booking')->insert([
            [
                'id_booking' => 1,
                'nomor_booking' => '01',
                'no_pelayanan' => '1',
                'id_users' => '1',
                'id_layanan' => '1',
                'tanggal' => now(),
                'status' => 'dipesan',
                'jam_booking' => '09:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_booking' => 2,
                'nomor_booking' => '02',
                'no_pelayanan' => '1',
                'id_users' => '1',
                'id_layanan' => '1',
                'tanggal' => now(),
                'status' => 'dipesan',
                'jam_booking' => '09:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_booking' => 3,
                'nomor_booking' => '03',
                'no_pelayanan' => '1',
                'id_users' => '1',
                'id_layanan' => '1',
                'tanggal' => now(),
                'status' => 'dipesan',
                'jam_booking' => '10:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_booking' => 4,
                'nomor_booking' => '04',
                'no_pelayanan' => '1',
                'id_users' => '1',
                'id_layanan' => '1',
                'tanggal' => now(),
                'status' => 'dipesan',
                'jam_booking' => '09:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_booking' => 5,
                'nomor_booking' => '05',
                'no_pelayanan' => '1',
                'id_users' => '1',
                'id_layanan' => '1',
                'tanggal' => now(),
                'status' => 'dibatalkan',
                'jam_booking' => '11:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_booking' => 6,
                'nomor_booking' => '06',
                'no_pelayanan' => '1',
                'id_users' => '1',
                'id_layanan' => '1',
                'tanggal' => now(),
                'status' => 'selesai',
                'jam_booking' => '12:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
