<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database with users.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'nombre' => 'Admin',
                'email' => 'root@uoc.edu',
                'password' => Hash::make('123456'),
                'rol' => 'admin',
                'telefono' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Técnico',
                'email' => 'tecnico@uoc.com',
                'password' => Hash::make('123456'),
                'rol' => 'tecnico',
                'telefono' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Cliente',
                'email' => 'cliente@uoc.com',
                'password' => Hash::make('123456'),
                'rol' => 'particular',
                'telefono' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'María García',
                'email' => 'maria.garcia@email.com',
                'password' => Hash::make('123456'),
                'rol' => 'particular',
                'telefono' => '612345678',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Carlos López',
                'email' => 'carlos.lopez@email.com',
                'password' => Hash::make('123456'),
                'rol' => 'particular',
                'telefono' => '623456789',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Ana Martínez',
                'email' => 'ana.martinez@email.com',
                'password' => Hash::make('123456'),
                'rol' => 'particular',
                'telefono' => '634567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
