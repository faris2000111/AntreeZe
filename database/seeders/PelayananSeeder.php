<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelayananSeeder extends Seeder
{
    public function run()
    {
        DB::table('pelayanan')->insert([
            [
                'id_pelayanan' => 1,
                'id_admin' => 1,
                'jenis' => 'Loket',
                'no_pelayanan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pelayanan' => 2,
                'id_admin' => 2,
                'jenis' => 'Loket',
                'no_pelayanan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pelayanan' => 3,
                'id_admin' => 3,
                'jenis' => 'Loket',
                'no_pelayanan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
