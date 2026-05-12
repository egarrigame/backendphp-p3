<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zonas = ['Centro', 'Norte', 'Sur', 'Este', 'Oeste', 'Ensanche'];

        foreach ($zonas as $zona) {
            DB::table('zonas')->insert([
                'nombre_zona' => $zona,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
