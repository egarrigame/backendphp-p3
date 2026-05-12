<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TecnicoSeeder extends Seeder
{
    /**
     * Seed the application's database with technicians.
     */
    public function run(): void
    {
        DB::table('tecnicos')->insert([
            [
                'usuario_id' => 2,
                'nombre_completo' => 'Técnico',
                'especialidad_id' => 1, // Fontanería
                'disponible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'usuario_id' => null,
                'nombre_completo' => 'Pedro Sánchez Ruiz',
                'especialidad_id' => 2, // Electricidad
                'disponible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'usuario_id' => null,
                'nombre_completo' => 'Luis Fernández Torres',
                'especialidad_id' => 3, // Aire acondicionado
                'disponible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
