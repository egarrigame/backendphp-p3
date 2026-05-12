<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ComisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates comision records for all Finalizada incidencias that have gestora_id.
     * monto_comision = precio_base * porcentaje_comision / 100
     */
    public function run(): void
    {
        // Get all Finalizada incidencias with gestora_id
        $incidencias = DB::table('incidencias')
            ->where('estado_id', 3) // Finalizada
            ->whereNotNull('gestora_id')
            ->where('precio_base', '>', 0)
            ->get();

        $mes = Carbon::now()->startOfMonth()->toDateString();

        foreach ($incidencias as $incidencia) {
            // Get the gestora's porcentaje_comision
            $gestora = DB::table('empresas_gestoras')
                ->where('id', $incidencia->gestora_id)
                ->first();

            if (!$gestora) {
                continue;
            }

            $montoComision = round($incidencia->precio_base * ($gestora->porcentaje_comision / 100), 2);

            DB::table('comisiones')->insert([
                'gestora_id' => $incidencia->gestora_id,
                'incidencia_id' => $incidencia->id,
                'monto_base' => $incidencia->precio_base,
                'porcentaje_aplicado' => $gestora->porcentaje_comision,
                'monto_comision' => $montoComision,
                'mes' => $mes,
                'pagada' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
