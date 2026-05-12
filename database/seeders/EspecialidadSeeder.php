<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EspecialidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especialidades = [
            'Fontanería',
            'Electricidad',
            'Aire acondicionado',
            'Bricolaje',
            'Cerrajería',
            'Pintura',
        ];

        foreach ($especialidades as $especialidad) {
            DB::table('especialidades')->insert([
                'nombre_especialidad' => $especialidad,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
