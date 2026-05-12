<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Seeders are called in dependency order:
     * independent tables first, then dependent ones.
     */
    public function run(): void
    {
        $this->call([
            EstadoSeeder::class,
            EspecialidadSeeder::class,
            ZonaSeeder::class,
            UserSeeder::class,
            TecnicoSeeder::class,
            GestoraSeeder::class,
            IncidenciaSeeder::class,
            ComisionSeeder::class,
        ]);
    }
}
