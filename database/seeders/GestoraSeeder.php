<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GestoraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('empresas_gestoras')->insert([
            [
                'nombre' => 'Fincas López',
                'cif' => 'B12345678',
                'direccion' => 'Calle Mayor 10, Madrid',
                'telefono' => '912345678',
                'email' => 'fincas@lopez.com',
                'password' => Hash::make('123456'),
                'porcentaje_comision' => 10.00,
                'activa' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Gestiones Martínez',
                'cif' => 'B87654321',
                'direccion' => 'Avenida de la Constitución 5, Madrid',
                'telefono' => '913456789',
                'email' => 'gestiones@martinez.com',
                'password' => Hash::make('123456'),
                'porcentaje_comision' => 5.00,
                'activa' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
