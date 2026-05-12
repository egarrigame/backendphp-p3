<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = ['Pendiente', 'Asignada', 'Finalizada', 'Cancelada'];

        foreach ($estados as $estado) {
            DB::table('estados')->insert([
                'nombre_estado' => $estado,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
