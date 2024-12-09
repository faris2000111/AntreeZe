<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    public function run()
    {
        DB::table('booking')->insert([
            [
                'id_booking' => 1,
                'nomor_booking' => 'E101',
                'no_pelayanan' => 0,
                'id_users' => 13,
                'id_layanan' => 1,
                'jam_booking' => '16:00:00',
                'tanggal' => '2024-11-13',
                'status' => 'dipesan',
                'status_dilewati' => 1,
                'catatan' => 'Anda telah dilewati karena datang terlambat, mohon pastikan datang 15 menit lebih awal, terimakasih!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_booking' => 2,
                'nomor_booking' => 'E102',
                'no_pelayanan' => 0,
                'id_users' => 13,
                'id_layanan' => 2,
                'jam_booking' => '17:00:00',
                'tanggal' => '2024-11-15',
                'status' => 'selesai',
                'status_dilewati' => 0,
                'catatan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_booking' => 3,
                'nomor_booking' => 'A011',
                'no_pelayanan' => 0,
                'id_users' => 13,
                'id_layanan' => 3,
                'jam_booking' => '08:00:00',
                'tanggal' => '2024-11-14',
                'status' => 'selesai',
                'status_dilewati' => 0,
                'catatan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
