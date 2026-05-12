<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class IncidenciaSeeder extends Seeder
{
    /**
     * Precio base por especialidad.
     */
    private const PRECIOS = [
        1 => 80.00,  // Fontanería
        2 => 65.00,  // Electricidad
        3 => 120.00, // Aire acondicionado
        4 => 45.00,  // Bricolaje
        5 => 90.00,  // Cerrajería
        6 => 70.00,  // Pintura
    ];

    /**
     * Genera un localizador único: R + 6 caracteres alfanuméricos en mayúsculas.
     */
    private function generarLocalizador(): string
    {
        return 'R' . strtoupper(Str::random(6));
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $incidencias = [
            // --- Incidencias de clientes directos (sin gestora) ---
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 3, // Cliente
                'tecnico_id' => 1,
                'especialidad_id' => 1, // Fontanería
                'estado_id' => 3, // Finalizada
                'zona_id' => 1, // Centro
                'gestora_id' => null,
                'nombre_residente' => null,
                'descripcion' => 'Fuga de agua en la tubería principal del baño',
                'direccion' => 'Calle Gran Vía 15, 3ºA, Madrid',
                'fecha_servicio' => $now->copy()->subDays(5)->setHour(9)->setMinute(0)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'estandar',
                'precio_base' => 80.00,
            ],
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 4, // María García
                'tecnico_id' => 2,
                'especialidad_id' => 2, // Electricidad
                'estado_id' => 3, // Finalizada
                'zona_id' => 2, // Norte
                'gestora_id' => null,
                'nombre_residente' => null,
                'descripcion' => 'Cortocircuito en el cuadro eléctrico de la cocina',
                'direccion' => 'Avenida de Burgos 22, 1ºB, Madrid',
                'fecha_servicio' => $now->copy()->subDays(4)->setHour(10)->setMinute(30)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'urgente',
                'precio_base' => 65.00,
            ],
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 5, // Carlos López
                'tecnico_id' => 3,
                'especialidad_id' => 3, // Aire acondicionado
                'estado_id' => 2, // Asignada
                'zona_id' => 3, // Sur
                'gestora_id' => null,
                'nombre_residente' => null,
                'descripcion' => 'El aire acondicionado no enfría correctamente',
                'direccion' => 'Calle de Villaverde 8, 5ºC, Madrid',
                'fecha_servicio' => $now->copy()->addDays(2)->setHour(11)->setMinute(0)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'estandar',
                'precio_base' => 120.00,
            ],
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 6, // Ana Martínez
                'tecnico_id' => null,
                'especialidad_id' => 4, // Bricolaje
                'estado_id' => 1, // Pendiente
                'zona_id' => 4, // Este
                'gestora_id' => null,
                'nombre_residente' => null,
                'descripcion' => 'Montaje de estantería y reparación de puerta corredera',
                'direccion' => 'Calle de Alcalá 120, 2ºD, Madrid',
                'fecha_servicio' => $now->copy()->addDays(4)->setHour(9)->setMinute(30)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'estandar',
                'precio_base' => 45.00,
            ],
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 3, // Cliente
                'tecnico_id' => null,
                'especialidad_id' => 5, // Cerrajería
                'estado_id' => 1, // Pendiente
                'zona_id' => 5, // Oeste
                'gestora_id' => null,
                'nombre_residente' => null,
                'descripcion' => 'Cambio de cerradura de la puerta principal',
                'direccion' => 'Calle de Princesa 45, 4ºA, Madrid',
                'fecha_servicio' => $now->copy()->addDays(5)->setHour(14)->setMinute(0)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'estandar',
                'precio_base' => 90.00,
            ],
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 4, // María García
                'tecnico_id' => 1,
                'especialidad_id' => 6, // Pintura
                'estado_id' => 3, // Finalizada
                'zona_id' => 6, // Ensanche
                'gestora_id' => null,
                'nombre_residente' => null,
                'descripcion' => 'Pintar salón completo y pasillo de entrada',
                'direccion' => 'Paseo de la Castellana 80, 6ºB, Madrid',
                'fecha_servicio' => $now->copy()->subDays(3)->setHour(8)->setMinute(0)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'estandar',
                'precio_base' => 70.00,
            ],
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 5, // Carlos López
                'tecnico_id' => 2,
                'especialidad_id' => 1, // Fontanería
                'estado_id' => 4, // Cancelada
                'zona_id' => 1, // Centro
                'gestora_id' => null,
                'nombre_residente' => null,
                'descripcion' => 'Reparación de cisterna del WC',
                'direccion' => 'Calle de Fuencarral 33, 1ºC, Madrid',
                'fecha_servicio' => $now->copy()->subDays(7)->setHour(16)->setMinute(0)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'estandar',
                'precio_base' => 80.00,
            ],
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 6, // Ana Martínez
                'tecnico_id' => 3,
                'especialidad_id' => 2, // Electricidad
                'estado_id' => 2, // Asignada
                'zona_id' => 2, // Norte
                'gestora_id' => null,
                'nombre_residente' => null,
                'descripcion' => 'Instalación de puntos de luz en terraza',
                'direccion' => 'Calle de Bravo Murillo 150, 3ºA, Madrid',
                'fecha_servicio' => $now->copy()->addDays(1)->setHour(10)->setMinute(0)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'urgente',
                'precio_base' => 65.00,
            ],

            // --- Incidencias creadas por Gestora 1 (Fincas López, 10%) ---
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 1, // Admin (sistema)
                'tecnico_id' => 1,
                'especialidad_id' => 1, // Fontanería
                'estado_id' => 3, // Finalizada
                'zona_id' => 1, // Centro
                'gestora_id' => 1, // Fincas López
                'nombre_residente' => 'Juan Pérez Gómez',
                'descripcion' => 'Rotura de tubería en la cocina comunitaria',
                'direccion' => 'Calle de Atocha 55, Bajo A, Madrid',
                'fecha_servicio' => $now->copy()->subDays(6)->setHour(9)->setMinute(0)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'urgente',
                'precio_base' => 80.00,
            ],
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 1, // Admin (sistema)
                'tecnico_id' => 3,
                'especialidad_id' => 3, // Aire acondicionado
                'estado_id' => 3, // Finalizada
                'zona_id' => 3, // Sur
                'gestora_id' => 1, // Fincas López
                'nombre_residente' => 'Laura Sánchez Díaz',
                'descripcion' => 'Revisión y recarga de gas del aire acondicionado central',
                'direccion' => 'Avenida de Andalucía 12, 2ºB, Madrid',
                'fecha_servicio' => $now->copy()->subDays(2)->setHour(11)->setMinute(0)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'estandar',
                'precio_base' => 120.00,
            ],
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 1, // Admin (sistema)
                'tecnico_id' => 2,
                'especialidad_id' => 5, // Cerrajería
                'estado_id' => 3, // Finalizada
                'zona_id' => 4, // Este
                'gestora_id' => 1, // Fincas López
                'nombre_residente' => 'Roberto Fernández Ruiz',
                'descripcion' => 'Cambio de bombín de la puerta del garaje comunitario',
                'direccion' => 'Calle de O\'Donnell 30, Portal 2, Madrid',
                'fecha_servicio' => $now->copy()->subDays(1)->setHour(15)->setMinute(0)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'estandar',
                'precio_base' => 90.00,
            ],
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 1, // Admin (sistema)
                'tecnico_id' => null,
                'especialidad_id' => 6, // Pintura
                'estado_id' => 1, // Pendiente
                'zona_id' => 5, // Oeste
                'gestora_id' => 1, // Fincas López
                'nombre_residente' => 'Carmen Ruiz López',
                'descripcion' => 'Pintar zonas comunes del portal y escalera',
                'direccion' => 'Calle de Alberto Aguilera 20, Madrid',
                'fecha_servicio' => $now->copy()->addDays(6)->setHour(8)->setMinute(30)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'estandar',
                'precio_base' => 70.00,
            ],
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 1, // Admin (sistema)
                'tecnico_id' => 1,
                'especialidad_id' => 4, // Bricolaje
                'estado_id' => 2, // Asignada
                'zona_id' => 6, // Ensanche
                'gestora_id' => 1, // Fincas López
                'nombre_residente' => 'Miguel Ángel Torres',
                'descripcion' => 'Reparación de buzones y señalización del portal',
                'direccion' => 'Calle de Serrano 90, Madrid',
                'fecha_servicio' => $now->copy()->addDays(3)->setHour(10)->setMinute(0)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'estandar',
                'precio_base' => 45.00,
            ],

            // --- Incidencias creadas por Gestora 2 (Gestiones Martínez, 5%) ---
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 1, // Admin (sistema)
                'tecnico_id' => 2,
                'especialidad_id' => 2, // Electricidad
                'estado_id' => 3, // Finalizada
                'zona_id' => 2, // Norte
                'gestora_id' => 2, // Gestiones Martínez
                'nombre_residente' => 'Patricia Moreno Gil',
                'descripcion' => 'Reparación del sistema eléctrico del ascensor',
                'direccion' => 'Calle de Orense 18, Madrid',
                'fecha_servicio' => $now->copy()->subDays(4)->setHour(9)->setMinute(30)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'urgente',
                'precio_base' => 65.00,
            ],
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 1, // Admin (sistema)
                'tecnico_id' => 1,
                'especialidad_id' => 1, // Fontanería
                'estado_id' => 3, // Finalizada
                'zona_id' => 6, // Ensanche
                'gestora_id' => 2, // Gestiones Martínez
                'nombre_residente' => 'Fernando Jiménez Navarro',
                'descripcion' => 'Reparación de bajante comunitaria con filtraciones',
                'direccion' => 'Paseo del Prado 28, Madrid',
                'fecha_servicio' => $now->copy()->subDays(2)->setHour(14)->setMinute(0)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'estandar',
                'precio_base' => 80.00,
            ],
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 1, // Admin (sistema)
                'tecnico_id' => 3,
                'especialidad_id' => 3, // Aire acondicionado
                'estado_id' => 2, // Asignada
                'zona_id' => 3, // Sur
                'gestora_id' => 2, // Gestiones Martínez
                'nombre_residente' => 'Isabel Romero Castro',
                'descripcion' => 'Instalación de sistema de climatización en sala de reuniones',
                'direccion' => 'Calle de Embajadores 40, 1ºA, Madrid',
                'fecha_servicio' => $now->copy()->addDays(3)->setHour(11)->setMinute(30)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'estandar',
                'precio_base' => 120.00,
            ],
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 1, // Admin (sistema)
                'tecnico_id' => null,
                'especialidad_id' => 4, // Bricolaje
                'estado_id' => 1, // Pendiente
                'zona_id' => 4, // Este
                'gestora_id' => 2, // Gestiones Martínez
                'nombre_residente' => 'Alejandro Vega Martín',
                'descripcion' => 'Reparación de barandilla de escalera comunitaria',
                'direccion' => 'Calle de Goya 65, Madrid',
                'fecha_servicio' => $now->copy()->addDays(7)->setHour(9)->setMinute(0)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'estandar',
                'precio_base' => 45.00,
            ],
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 1, // Admin (sistema)
                'tecnico_id' => 2,
                'especialidad_id' => 5, // Cerrajería
                'estado_id' => 3, // Finalizada
                'zona_id' => 5, // Oeste
                'gestora_id' => 2, // Gestiones Martínez
                'nombre_residente' => 'Sofía Delgado Herrera',
                'descripcion' => 'Cambio de cerraduras de trasteros comunitarios',
                'direccion' => 'Calle de Argüelles 12, Madrid',
                'fecha_servicio' => $now->copy()->subDays(1)->setHour(16)->setMinute(0)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'estandar',
                'precio_base' => 90.00,
            ],
            [
                'localizador' => $this->generarLocalizador(),
                'cliente_id' => 3, // Cliente
                'tecnico_id' => 1,
                'especialidad_id' => 6, // Pintura
                'estado_id' => 3, // Finalizada
                'zona_id' => 1, // Centro
                'gestora_id' => null,
                'nombre_residente' => null,
                'descripcion' => 'Reparación de humedades y pintura en dormitorio',
                'direccion' => 'Calle de Toledo 18, 4ºB, Madrid',
                'fecha_servicio' => $now->copy()->setHour(8)->setMinute(30)->format('Y-m-d H:i:s'),
                'tipo_urgencia' => 'estandar',
                'precio_base' => 70.00,
            ],
        ];

        foreach ($incidencias as $incidencia) {
            DB::table('incidencias')->insert(array_merge($incidencia, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
