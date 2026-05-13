<?php
/**
 * Production seeder script - bypasses artisan CLI (no DOMDocument needed)
 * Run: php seed_production.php
 */

// Bootstrap Laravel without the CLI kernel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

echo "Seeding database...\n";

// Estados
DB::table('estados')->insert([
    ['nombre_estado' => 'Pendiente', 'created_at' => now(), 'updated_at' => now()],
    ['nombre_estado' => 'Asignada', 'created_at' => now(), 'updated_at' => now()],
    ['nombre_estado' => 'Finalizada', 'created_at' => now(), 'updated_at' => now()],
    ['nombre_estado' => 'Cancelada', 'created_at' => now(), 'updated_at' => now()],
]);
echo "  Estados: OK\n";

// Especialidades
DB::table('especialidades')->insert([
    ['nombre_especialidad' => 'Fontanería', 'created_at' => now(), 'updated_at' => now()],
    ['nombre_especialidad' => 'Electricidad', 'created_at' => now(), 'updated_at' => now()],
    ['nombre_especialidad' => 'Aire acondicionado', 'created_at' => now(), 'updated_at' => now()],
    ['nombre_especialidad' => 'Bricolaje', 'created_at' => now(), 'updated_at' => now()],
    ['nombre_especialidad' => 'Cerrajería', 'created_at' => now(), 'updated_at' => now()],
    ['nombre_especialidad' => 'Pintura', 'created_at' => now(), 'updated_at' => now()],
]);
echo "  Especialidades: OK\n";

// Zonas
DB::table('zonas')->insert([
    ['nombre_zona' => 'Centro', 'created_at' => now(), 'updated_at' => now()],
    ['nombre_zona' => 'Norte', 'created_at' => now(), 'updated_at' => now()],
    ['nombre_zona' => 'Sur', 'created_at' => now(), 'updated_at' => now()],
    ['nombre_zona' => 'Este', 'created_at' => now(), 'updated_at' => now()],
    ['nombre_zona' => 'Oeste', 'created_at' => now(), 'updated_at' => now()],
    ['nombre_zona' => 'Ensanche', 'created_at' => now(), 'updated_at' => now()],
]);
echo "  Zonas: OK\n";

// Usuarios
$password = Hash::make('123456');
DB::table('usuarios')->insert([
    ['nombre' => 'Admin', 'email' => 'root@uoc.edu', 'password' => $password, 'rol' => 'admin', 'telefono' => '600000001', 'created_at' => now(), 'updated_at' => now()],
    ['nombre' => 'Técnico', 'email' => 'tecnico@uoc.com', 'password' => $password, 'rol' => 'tecnico', 'telefono' => '600000002', 'created_at' => now(), 'updated_at' => now()],
    ['nombre' => 'Cliente', 'email' => 'cliente@uoc.com', 'password' => $password, 'rol' => 'particular', 'telefono' => '600000003', 'created_at' => now(), 'updated_at' => now()],
    ['nombre' => 'María García', 'email' => 'maria@gmail.com', 'password' => $password, 'rol' => 'particular', 'telefono' => '612345678', 'created_at' => now(), 'updated_at' => now()],
    ['nombre' => 'Carlos López', 'email' => 'carlos@gmail.com', 'password' => $password, 'rol' => 'particular', 'telefono' => '623456789', 'created_at' => now(), 'updated_at' => now()],
    ['nombre' => 'Ana Martínez', 'email' => 'ana@gmail.com', 'password' => $password, 'rol' => 'particular', 'telefono' => '634567890', 'created_at' => now(), 'updated_at' => now()],
]);
echo "  Usuarios: OK\n";

// Técnicos
DB::table('tecnicos')->insert([
    ['usuario_id' => 2, 'nombre_completo' => 'Técnico Test', 'especialidad_id' => 1, 'disponible' => true, 'created_at' => now(), 'updated_at' => now()],
    ['usuario_id' => null, 'nombre_completo' => 'Pedro Sánchez Ruiz', 'especialidad_id' => 2, 'disponible' => true, 'created_at' => now(), 'updated_at' => now()],
    ['usuario_id' => null, 'nombre_completo' => 'Luis Fernández Gil', 'especialidad_id' => 3, 'disponible' => true, 'created_at' => now(), 'updated_at' => now()],
]);
echo "  Técnicos: OK\n";

// Gestoras
DB::table('empresas_gestoras')->insert([
    ['nombre' => 'Fincas López', 'cif' => 'B12345678', 'direccion' => 'Calle Mayor 10, Madrid', 'telefono' => '910000001', 'email' => 'fincas@lopez.com', 'password' => $password, 'porcentaje_comision' => 10.00, 'activa' => true, 'created_at' => now(), 'updated_at' => now()],
    ['nombre' => 'Gestiones Martínez', 'cif' => 'B87654321', 'direccion' => 'Avenida de la Constitución 5, Madrid', 'telefono' => '910000002', 'email' => 'gestiones@martinez.com', 'password' => $password, 'porcentaje_comision' => 5.00, 'activa' => true, 'created_at' => now(), 'updated_at' => now()],
]);
echo "  Gestoras: OK\n";

// Incidencias
$now = Carbon::now();
$precios = [1 => 80, 2 => 65, 3 => 120, 4 => 45, 5 => 90, 6 => 70];

$incidencias = [
    ['cliente_id'=>3,'tecnico_id'=>1,'especialidad_id'=>1,'estado_id'=>3,'zona_id'=>1,'gestora_id'=>null,'nombre_residente'=>null,'descripcion'=>'Fuga de agua en tubería','direccion'=>'Gran Vía 15, Madrid','fecha_servicio'=>$now->copy()->subDays(5)->setHour(9)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'estandar'],
    ['cliente_id'=>4,'tecnico_id'=>2,'especialidad_id'=>2,'estado_id'=>3,'zona_id'=>2,'gestora_id'=>null,'nombre_residente'=>null,'descripcion'=>'Cortocircuito en cocina','direccion'=>'Av. Burgos 22, Madrid','fecha_servicio'=>$now->copy()->subDays(4)->setHour(10)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'urgente'],
    ['cliente_id'=>5,'tecnico_id'=>3,'especialidad_id'=>3,'estado_id'=>2,'zona_id'=>3,'gestora_id'=>null,'nombre_residente'=>null,'descripcion'=>'Aire no enfría','direccion'=>'C/ Villaverde 8, Madrid','fecha_servicio'=>$now->copy()->addDays(2)->setHour(11)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'estandar'],
    ['cliente_id'=>6,'tecnico_id'=>null,'especialidad_id'=>4,'estado_id'=>1,'zona_id'=>4,'gestora_id'=>null,'nombre_residente'=>null,'descripcion'=>'Montaje estantería','direccion'=>'C/ Alcalá 120, Madrid','fecha_servicio'=>$now->copy()->addDays(4)->setHour(9)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'estandar'],
    ['cliente_id'=>3,'tecnico_id'=>null,'especialidad_id'=>5,'estado_id'=>1,'zona_id'=>5,'gestora_id'=>null,'nombre_residente'=>null,'descripcion'=>'Cambio cerradura','direccion'=>'C/ Princesa 45, Madrid','fecha_servicio'=>$now->copy()->addDays(5)->setHour(14)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'estandar'],
    ['cliente_id'=>4,'tecnico_id'=>1,'especialidad_id'=>6,'estado_id'=>3,'zona_id'=>6,'gestora_id'=>null,'nombre_residente'=>null,'descripcion'=>'Pintar salón','direccion'=>'Paseo Castellana 80, Madrid','fecha_servicio'=>$now->copy()->subDays(3)->setHour(8)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'estandar'],
    ['cliente_id'=>5,'tecnico_id'=>2,'especialidad_id'=>1,'estado_id'=>4,'zona_id'=>1,'gestora_id'=>null,'nombre_residente'=>null,'descripcion'=>'Reparación cisterna','direccion'=>'C/ Fuencarral 33, Madrid','fecha_servicio'=>$now->copy()->subDays(7)->setHour(16)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'estandar'],
    ['cliente_id'=>6,'tecnico_id'=>3,'especialidad_id'=>2,'estado_id'=>2,'zona_id'=>2,'gestora_id'=>null,'nombre_residente'=>null,'descripcion'=>'Puntos de luz terraza','direccion'=>'C/ Bravo Murillo 150, Madrid','fecha_servicio'=>$now->copy()->addDays(1)->setHour(10)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'urgente'],
    // Gestora 1
    ['cliente_id'=>1,'tecnico_id'=>1,'especialidad_id'=>1,'estado_id'=>3,'zona_id'=>1,'gestora_id'=>1,'nombre_residente'=>'Juan Pérez','descripcion'=>'Rotura tubería cocina','direccion'=>'C/ Atocha 55, Madrid','fecha_servicio'=>$now->copy()->subDays(6)->setHour(9)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'urgente'],
    ['cliente_id'=>1,'tecnico_id'=>3,'especialidad_id'=>3,'estado_id'=>3,'zona_id'=>3,'gestora_id'=>1,'nombre_residente'=>'Laura Sánchez','descripcion'=>'Recarga gas aire','direccion'=>'Av. Andalucía 12, Madrid','fecha_servicio'=>$now->copy()->subDays(2)->setHour(11)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'estandar'],
    ['cliente_id'=>1,'tecnico_id'=>2,'especialidad_id'=>5,'estado_id'=>3,'zona_id'=>4,'gestora_id'=>1,'nombre_residente'=>'Roberto Fernández','descripcion'=>'Cambio bombín garaje','direccion'=>'C/ O\'Donnell 30, Madrid','fecha_servicio'=>$now->copy()->subDays(1)->setHour(15)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'estandar'],
    ['cliente_id'=>1,'tecnico_id'=>null,'especialidad_id'=>6,'estado_id'=>1,'zona_id'=>5,'gestora_id'=>1,'nombre_residente'=>'Carmen Ruiz','descripcion'=>'Pintar zonas comunes','direccion'=>'C/ Alberto Aguilera 20, Madrid','fecha_servicio'=>$now->copy()->addDays(6)->setHour(8)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'estandar'],
    ['cliente_id'=>1,'tecnico_id'=>1,'especialidad_id'=>4,'estado_id'=>2,'zona_id'=>6,'gestora_id'=>1,'nombre_residente'=>'Miguel Torres','descripcion'=>'Reparación buzones','direccion'=>'C/ Serrano 90, Madrid','fecha_servicio'=>$now->copy()->addDays(3)->setHour(10)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'estandar'],
    // Gestora 2
    ['cliente_id'=>1,'tecnico_id'=>2,'especialidad_id'=>2,'estado_id'=>3,'zona_id'=>2,'gestora_id'=>2,'nombre_residente'=>'Patricia Moreno','descripcion'=>'Sistema eléctrico ascensor','direccion'=>'C/ Orense 18, Madrid','fecha_servicio'=>$now->copy()->subDays(4)->setHour(9)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'urgente'],
    ['cliente_id'=>1,'tecnico_id'=>1,'especialidad_id'=>1,'estado_id'=>3,'zona_id'=>6,'gestora_id'=>2,'nombre_residente'=>'Fernando Jiménez','descripcion'=>'Bajante con filtraciones','direccion'=>'Paseo del Prado 28, Madrid','fecha_servicio'=>$now->copy()->subDays(2)->setHour(14)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'estandar'],
    ['cliente_id'=>1,'tecnico_id'=>3,'especialidad_id'=>3,'estado_id'=>2,'zona_id'=>3,'gestora_id'=>2,'nombre_residente'=>'Isabel Romero','descripcion'=>'Climatización sala reuniones','direccion'=>'C/ Embajadores 40, Madrid','fecha_servicio'=>$now->copy()->addDays(3)->setHour(11)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'estandar'],
    ['cliente_id'=>1,'tecnico_id'=>null,'especialidad_id'=>4,'estado_id'=>1,'zona_id'=>4,'gestora_id'=>2,'nombre_residente'=>'Alejandro Vega','descripcion'=>'Barandilla escalera','direccion'=>'C/ Goya 65, Madrid','fecha_servicio'=>$now->copy()->addDays(7)->setHour(9)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'estandar'],
    ['cliente_id'=>1,'tecnico_id'=>2,'especialidad_id'=>5,'estado_id'=>3,'zona_id'=>5,'gestora_id'=>2,'nombre_residente'=>'Sofía Delgado','descripcion'=>'Cerraduras trasteros','direccion'=>'C/ Argüelles 12, Madrid','fecha_servicio'=>$now->copy()->subDays(1)->setHour(16)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'estandar'],
    ['cliente_id'=>3,'tecnico_id'=>1,'especialidad_id'=>6,'estado_id'=>3,'zona_id'=>1,'gestora_id'=>null,'nombre_residente'=>null,'descripcion'=>'Humedades dormitorio','direccion'=>'C/ Toledo 18, Madrid','fecha_servicio'=>$now->copy()->setHour(8)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'estandar'],
    ['cliente_id'=>1,'tecnico_id'=>2,'especialidad_id'=>1,'estado_id'=>3,'zona_id'=>2,'gestora_id'=>1,'nombre_residente'=>'Antonio García','descripcion'=>'Fuga calefacción central','direccion'=>'C/ Velázquez 44, Madrid','fecha_servicio'=>$now->copy()->subDays(3)->setHour(10)->format('Y-m-d H:i:s'),'tipo_urgencia'=>'estandar'],
];

foreach ($incidencias as $inc) {
    DB::table('incidencias')->insert(array_merge($inc, [
        'localizador' => 'R' . strtoupper(Str::random(6)),
        'precio_base' => $precios[$inc['especialidad_id']],
        'created_at' => now(),
        'updated_at' => now(),
    ]));
}
echo "  Incidencias (20): OK\n";

// Comisiones for Finalizada + gestora_id
$finalizadas = DB::table('incidencias')
    ->where('estado_id', 3)
    ->whereNotNull('gestora_id')
    ->where('precio_base', '>', 0)
    ->get();

foreach ($finalizadas as $inc) {
    $gestora = DB::table('empresas_gestoras')->find($inc->gestora_id);
    DB::table('comisiones')->insert([
        'gestora_id' => $inc->gestora_id,
        'incidencia_id' => $inc->id,
        'monto_base' => $inc->precio_base,
        'porcentaje_aplicado' => $gestora->porcentaje_comision,
        'monto_comision' => round($inc->precio_base * ($gestora->porcentaje_comision / 100), 2),
        'mes' => Carbon::now()->startOfMonth()->toDateString(),
        'pagada' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
echo "  Comisiones: OK\n";

echo "\nDone! Database seeded successfully.\n";
