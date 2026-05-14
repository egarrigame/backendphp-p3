# Plan de Implementación — ReparaYa Producto 3 (Laravel)

## Objetivo

Migrar ReparaYa (Producto 2, PHP MVC puro) a Laravel 10, añadir módulo B2B para Administradores de Fincas con comisiones, API REST, y desplegar en servidor UOC en `dominio.com/producto3`. Puntuación objetivo: **35/35**.

---

## Rúbrica — Criterios para Máxima Puntuación

| # | Criterio | Pts | Requisito máximo |
|---|----------|-----|------------------|
| 1 | Base de datos | 1 | Migraciones Laravel para TODAS las tablas + nuevas (gestoras, comisiones, zonas) |
| 2 | Adaptación Laravel MVC | 8 | 100% migrado: Rutas, Controladores, Eloquent, Blade |
| 3 | Panel Admin Fincas B2B | 5 | Gestoras crean servicios por vecinos + ven comisiones acumuladas |
| 4 | Gestión Comisiones Admin | 4 | Admin alta gestoras + listado reservas + liquidación mensual |
| 5 | Web Service API REST | 4 | GET /api/servicios/zonas → JSON {zona, total_servicios, porcentaje} |
| 6 | GIT | 3 | Historial impecable + ramas por funcionalidad |
| 7 | Defensa Técnica | 6 | Dominio de Rutas, Eloquent, Middlewares |
| 8 | IA + Mapa Conceptual | 4 | 4 prompts documentados + mapa a MANO |

---

## Servidor UOC (Producción)

```
SSH:        ssh uocx1@fp064.techlab.uoc.edu -p 55000
Password:   XXXXXXX
Web:        https://fp064.techlab.uoc.edu/~uocx1/producto3
Carpeta:    ~/public_html/producto3/
BD Host:    localhost
BD User:    wordpress1
BD Pass:    XXXXXXX
BD Name:    reparaya
PHPMyAdmin: http://fp064.techlab.uoc.edu/bbdd/
SFTP:       sftp -P 55000 uocx1@fp064.techlab.uoc.edu
```

---

## Arquitectura Actual (Producto 2)

### Controladores
- `AuthController` → showLogin(), login(), showRegister(), register(), logout()
- `UserController` → perfil(), updatePerfil(), dashboardCliente()
- `IncidenciaController` → create(), store(), misAvisos(), cancel()
- `AdminController` → dashboard(), incidenciaDetalle(), asignarTecnico(), calendario(), crearIncidencia(), storeIncidencia(), editIncidencia(), updateIncidencia(), cancelarIncidencia()
- `TecnicoController` → index(), store(), update(), delete(), agenda()
- `EspecialidadController` → index(), create(), store(), edit(), update(), delete()

### Modelos
- `User` → findByEmail(), findById(), create(), updateProfile(), getClientes()
- `Incidencia` → create(), findByCliente(), findAll(), findByTecnico(), findById(), cancel(), cancelAdmin(), assignTecnico(), updateAdmin(), generarLocalizador(), validarRegla48h(), getEstados()
- `Tecnico` → getAll(), findById(), findByUsuarioId(), create(), update(), delete(), getAgenda()
- `Especialidad` → getAll(), findById(), create(), update(), delete()

### Vistas
- `auth/` → login.php, register.php
- `cliente/` → dashboard.php, mis_avisos.php, nueva_incidencia.php
- `admin/` → dashboard.php, incidencias.php, crear_incidencia.php, editar_incidencia.php, tecnicos.php, especialidades.php, calendario.php
- `tecnico/` → agenda.php
- `user/` → perfil.php
- `layouts/` → app.php, auth.php

### Base de datos actual
- `usuarios` (id, nombre, email, password, rol[admin/tecnico/particular], telefono, created_at)
- `especialidades` (id, nombre_especialidad)
- `estados` (id, nombre_estado) → Pendiente, Asignada, Finalizada, Cancelada
- `tecnicos` (id, usuario_id FK, nombre_completo, especialidad_id FK, disponible)
- `incidencias` (id, localizador, cliente_id FK, tecnico_id FK, especialidad_id FK, estado_id FK, descripcion, direccion, fecha_servicio, tipo_urgencia[estandar/urgente], created_at)

### Credenciales de prueba
- Admin: root@uoc.edu / 123456
- Técnico: tecnico@uoc.com / 123456
- Cliente: cliente@uoc.com / 123456

### Stack
- PHP 8.2, PDO, Apache .htaccess rewrite
- Bootstrap 5.3, Google Fonts Inter, CSS custom (gradient navbar indigo, cards hover, filas urgente/estándar coloreadas)


---

## Nuevas Tablas para Producto 3

### `zonas`
- id, nombre_zona, created_at, updated_at
- Datos: Centro, Norte, Sur, Este, Oeste, Ensanche

### `empresas_gestoras`
- id, nombre, cif (unique), direccion, telefono, email (unique), password, porcentaje_comision (decimal 5,2 default 10.00), activa (boolean default true), created_at, updated_at

### `comisiones`
- id, gestora_id (FK empresas_gestoras), incidencia_id (FK incidencias), monto_base (decimal 8,2), porcentaje_aplicado (decimal 5,2), monto_comision (decimal 8,2), mes (date), pagada (boolean default false), created_at, updated_at

### Modificaciones a `incidencias`
- + zona_id (FK zonas, nullable)
- + gestora_id (FK empresas_gestoras, nullable)
- + nombre_residente (string nullable)
- + precio_base (decimal 8,2 default 0)

### Precios base por especialidad
| Especialidad | Precio |
|---|---|
| Fontanería | 80.00€ |
| Electricidad | 65.00€ |
| Aire acondicionado | 120.00€ |
| Bricolaje | 45.00€ |
| Cerrajería | 90.00€ |
| Pintura | 70.00€ |

---

## TAREAS DE IMPLEMENTACIÓN (Paso a Paso)

---

### TASK 1: Inicialización del proyecto Laravel

**Rama git:** `feature/laravel-init`

1. Crear proyecto Laravel 10: `composer create-project laravel/laravel producto3`
2. Configurar `.env`:
   ```
   APP_NAME=ReparaYa
   APP_ENV=local
   APP_URL=http://localhost:8000/producto3
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=reparaya
   DB_USERNAME=root
   DB_PASSWORD=1234
   ```
3. Configurar `RouteServiceProvider.php` para que las rutas web tengan prefix `/producto3`:
   ```php
   Route::middleware('web')->prefix('producto3')->group(base_path('routes/web.php'));
   Route::middleware('api')->prefix('producto3/api')->group(base_path('routes/api.php'));
   ```
4. En `config/app.php` configurar `'asset_url' => env('ASSET_URL', '/producto3')` 
5. Crear `.gitignore` Laravel estándar (vendor/, node_modules/, .env, storage/logs/)
6. Commit: `feat: inicializar proyecto Laravel 10 con configuración base /producto3`

---

### TASK 2: Migraciones — Tablas existentes

**Rama git:** `feature/laravel-init`

Crear migraciones en este orden:

1. `create_usuarios_table`:
   ```php
   Schema::create('usuarios', function (Blueprint $table) {
       $table->id();
       $table->string('nombre', 100);
       $table->string('email', 100)->unique();
       $table->string('password', 255);
       $table->enum('rol', ['admin', 'tecnico', 'particular'])->default('particular');
       $table->string('telefono', 20)->nullable();
       $table->timestamps();
   });
   ```

2. `create_especialidades_table`:
   ```php
   Schema::create('especialidades', function (Blueprint $table) {
       $table->id();
       $table->string('nombre_especialidad', 50);
       $table->timestamps();
   });
   ```

3. `create_estados_table`:
   ```php
   Schema::create('estados', function (Blueprint $table) {
       $table->id();
       $table->string('nombre_estado', 50);
       $table->timestamps();
   });
   ```

4. `create_zonas_table`:
   ```php
   Schema::create('zonas', function (Blueprint $table) {
       $table->id();
       $table->string('nombre_zona', 100);
       $table->timestamps();
   });
   ```

5. `create_tecnicos_table`:
   ```php
   Schema::create('tecnicos', function (Blueprint $table) {
       $table->id();
       $table->foreignId('usuario_id')->nullable()->unique()->constrained('usuarios')->nullOnDelete();
       $table->string('nombre_completo', 100);
       $table->foreignId('especialidad_id')->constrained('especialidades');
       $table->boolean('disponible')->default(true);
       $table->timestamps();
   });
   ```

6. `create_incidencias_table`:
   ```php
   Schema::create('incidencias', function (Blueprint $table) {
       $table->id();
       $table->string('localizador', 12)->unique();
       $table->foreignId('cliente_id')->constrained('usuarios');
       $table->foreignId('tecnico_id')->nullable()->constrained('tecnicos');
       $table->foreignId('especialidad_id')->constrained('especialidades');
       $table->foreignId('estado_id')->default(1)->constrained('estados');
       $table->foreignId('zona_id')->nullable()->constrained('zonas');
       $table->text('descripcion');
       $table->string('direccion', 255);
       $table->dateTime('fecha_servicio');
       $table->enum('tipo_urgencia', ['estandar', 'urgente'])->default('estandar');
       $table->decimal('precio_base', 8, 2)->default(0);
       $table->timestamps();
       $table->index('cliente_id');
       $table->index('tecnico_id');
   });
   ```

Commit: `feat: crear migraciones para tablas existentes (usuarios, especialidades, estados, zonas, tecnicos, incidencias)`

---

### TASK 3: Migraciones — Tablas B2B nuevas

**Rama git:** `feature/laravel-init`

1. `create_empresas_gestoras_table`:
   ```php
   Schema::create('empresas_gestoras', function (Blueprint $table) {
       $table->id();
       $table->string('nombre', 150);
       $table->string('cif', 20)->unique();
       $table->string('direccion', 255);
       $table->string('telefono', 20);
       $table->string('email', 100)->unique();
       $table->string('password', 255);
       $table->decimal('porcentaje_comision', 5, 2)->default(10.00);
       $table->boolean('activa')->default(true);
       $table->timestamps();
   });
   ```

2. `add_gestora_fields_to_incidencias_table`:
   ```php
   Schema::table('incidencias', function (Blueprint $table) {
       $table->foreignId('gestora_id')->nullable()->after('zona_id')->constrained('empresas_gestoras');
       $table->string('nombre_residente', 100)->nullable()->after('gestora_id');
   });
   ```

3. `create_comisiones_table`:
   ```php
   Schema::create('comisiones', function (Blueprint $table) {
       $table->id();
       $table->foreignId('gestora_id')->constrained('empresas_gestoras');
       $table->foreignId('incidencia_id')->constrained('incidencias');
       $table->decimal('monto_base', 8, 2);
       $table->decimal('porcentaje_aplicado', 5, 2);
       $table->decimal('monto_comision', 8, 2);
       $table->date('mes');
       $table->boolean('pagada')->default(false);
       $table->timestamps();
   });
   ```

Commit: `feat: crear migraciones B2B (empresas_gestoras, comisiones, campos gestora en incidencias)`

---

### TASK 4: Seeders con datos realistas

**Rama git:** `feature/laravel-init`

Crear seeders en este orden en `DatabaseSeeder.php`:

1. **EstadoSeeder**: Pendiente, Asignada, Finalizada, Cancelada
2. **EspecialidadSeeder**: Fontanería, Electricidad, Aire acondicionado, Bricolaje, Cerrajería, Pintura
3. **ZonaSeeder**: Centro, Norte, Sur, Este, Oeste, Ensanche
4. **UserSeeder**:
   - Admin: nombre=Admin, email=root@uoc.edu, password=bcrypt('123456'), rol=admin
   - Técnico: nombre=Tecnico Test, email=tecnico@uoc.com, password=bcrypt('123456'), rol=tecnico
   - Cliente: nombre=Cliente Test, email=cliente@uoc.com, password=bcrypt('123456'), rol=particular
   - 3 clientes adicionales con datos realistas
5. **TecnicoSeeder**: vincular usuario técnico con Fontanería + crear 2 técnicos más
6. **GestoraSeeder**:
   - Fincas López: CIF=B12345678, email=fincas@lopez.com, password=bcrypt('123456'), comisión=10%
   - Gestiones Martínez: CIF=B87654321, email=gestiones@martinez.com, password=bcrypt('123456'), comisión=5%
7. **IncidenciaSeeder**: 20 incidencias repartidas entre zonas y clientes, algunas con gestora_id, precios_base según especialidad, varias en estado Finalizada
8. **ComisionSeeder**: Crear comisiones para incidencias finalizadas que tengan gestora_id (monto_comision = precio_base * porcentaje / 100)

Commit: `feat: crear seeders con datos realistas para demostración`


---

### TASK 5: Modelos Eloquent con relaciones

**Rama git:** `feature/crud-migration`

Crear modelos en `app/Models/`:

1. **User** (`tabla: usuarios`):
   ```php
   protected $table = 'usuarios';
   protected $fillable = ['nombre', 'email', 'password', 'rol', 'telefono'];
   protected $hidden = ['password'];
   
   public function incidencias() { return $this->hasMany(Incidencia::class, 'cliente_id'); }
   public function tecnico() { return $this->hasOne(Tecnico::class, 'usuario_id'); }
   ```

2. **Especialidad** (`tabla: especialidades`):
   ```php
   protected $table = 'especialidades';
   protected $fillable = ['nombre_especialidad'];
   
   public function tecnicos() { return $this->hasMany(Tecnico::class); }
   public function incidencias() { return $this->hasMany(Incidencia::class); }
   ```

3. **Estado** (`tabla: estados`):
   ```php
   protected $table = 'estados';
   protected $fillable = ['nombre_estado'];
   
   public function incidencias() { return $this->hasMany(Incidencia::class); }
   ```

4. **Zona** (`tabla: zonas`):
   ```php
   protected $table = 'zonas';
   protected $fillable = ['nombre_zona'];
   
   public function incidencias() { return $this->hasMany(Incidencia::class); }
   ```

5. **Tecnico** (`tabla: tecnicos`):
   ```php
   protected $table = 'tecnicos';
   protected $fillable = ['usuario_id', 'nombre_completo', 'especialidad_id', 'disponible'];
   protected $casts = ['disponible' => 'boolean'];
   
   public function usuario() { return $this->belongsTo(User::class, 'usuario_id'); }
   public function especialidad() { return $this->belongsTo(Especialidad::class); }
   public function incidencias() { return $this->hasMany(Incidencia::class); }
   ```

6. **Incidencia** (`tabla: incidencias`):
   ```php
   protected $table = 'incidencias';
   protected $fillable = ['localizador', 'cliente_id', 'tecnico_id', 'especialidad_id', 'estado_id', 'zona_id', 'gestora_id', 'nombre_residente', 'descripcion', 'direccion', 'fecha_servicio', 'tipo_urgencia', 'precio_base'];
   protected $casts = ['fecha_servicio' => 'datetime', 'precio_base' => 'decimal:2'];
   
   public function cliente() { return $this->belongsTo(User::class, 'cliente_id'); }
   public function tecnico() { return $this->belongsTo(Tecnico::class); }
   public function especialidad() { return $this->belongsTo(Especialidad::class); }
   public function estado() { return $this->belongsTo(Estado::class); }
   public function zona() { return $this->belongsTo(Zona::class); }
   public function gestora() { return $this->belongsTo(EmpresaGestora::class, 'gestora_id'); }
   public function comision() { return $this->hasOne(Comision::class); }
   
   public static function generarLocalizador(): string {
       return 'R' . strtoupper(substr(uniqid(), -6));
   }
   
   public function scopeFinalizadas($query) {
       return $query->whereHas('estado', fn($q) => $q->where('nombre_estado', 'Finalizada'));
   }
   ```

7. **EmpresaGestora** (`tabla: empresas_gestoras`):
   ```php
   protected $table = 'empresas_gestoras';
   protected $fillable = ['nombre', 'cif', 'direccion', 'telefono', 'email', 'password', 'porcentaje_comision', 'activa'];
   protected $hidden = ['password'];
   protected $casts = ['porcentaje_comision' => 'decimal:2', 'activa' => 'boolean'];
   
   public function incidencias() { return $this->hasMany(Incidencia::class, 'gestora_id'); }
   public function comisiones() { return $this->hasMany(Comision::class, 'gestora_id'); }
   ```

8. **Comision** (`tabla: comisiones`):
   ```php
   protected $table = 'comisiones';
   protected $fillable = ['gestora_id', 'incidencia_id', 'monto_base', 'porcentaje_aplicado', 'monto_comision', 'mes', 'pagada'];
   protected $casts = ['mes' => 'date', 'pagada' => 'boolean', 'monto_base' => 'decimal:2', 'monto_comision' => 'decimal:2'];
   
   public function gestora() { return $this->belongsTo(EmpresaGestora::class, 'gestora_id'); }
   public function incidencia() { return $this->belongsTo(Incidencia::class); }
   ```

Commit: `feat: crear modelos Eloquent con relaciones (User, Incidencia, Tecnico, Especialidad, Estado, Zona, EmpresaGestora, Comision)`

---

### TASK 6: Middleware de autenticación y roles

**Rama git:** `feature/auth-middleware`

1. Crear `app/Http/Middleware/RoleMiddleware.php`:
   ```php
   public function handle($request, Closure $next, ...$roles)
   {
       if (!session()->has('user')) {
           return redirect('/producto3/login');
       }
       if (!in_array(session('user.rol'), $roles)) {
           abort(403, 'Acceso no autorizado');
       }
       return $next($request);
   }
   ```

2. Crear `app/Http/Middleware/GestoraMiddleware.php`:
   ```php
   public function handle($request, Closure $next)
   {
       if (!session()->has('gestora')) {
           return redirect('/producto3/login');
       }
       return $next($request);
   }
   ```

3. Crear `app/Http/Middleware/AuthCheck.php`:
   ```php
   public function handle($request, Closure $next)
   {
       if (!session()->has('user') && !session()->has('gestora')) {
           return redirect('/producto3/login');
       }
       return $next($request);
   }
   ```

4. Registrar en `app/Http/Kernel.php` → `$middlewareAliases`:
   ```php
   'role' => \App\Http\Middleware\RoleMiddleware::class,
   'gestora' => \App\Http\Middleware\GestoraMiddleware::class,
   'authcheck' => \App\Http\Middleware\AuthCheck::class,
   ```

Commit: `feat: implementar middlewares de autenticación y control de roles`

---

### TASK 7: AuthController — Login/Register/Logout

**Rama git:** `feature/auth-middleware`

Crear `app/Http/Controllers/AuthController.php`:

- **showLogin()**: Renderiza `auth.login`. Si ya hay sesión, redirige según rol.
- **login(Request $request)**:
  1. Validar email + password requeridos
  2. Buscar en tabla `usuarios` por email
  3. Si no existe → buscar en tabla `empresas_gestoras` por email
  4. Verificar password con `password_verify()`
  5. Si es usuario normal → guardar en `session('user')` con id, nombre, email, rol → redirigir según rol
  6. Si es gestora → guardar en `session('gestora')` con id, nombre, email → redirigir a gestora/dashboard
  7. Si falla → redirect back con error
- **showRegister()**: Renderiza `auth.register`
- **register(Request $request)**:
  1. Validar: nombre, email (unique:usuarios), telefono, password (min:4)
  2. Crear usuario con rol 'particular', password hasheada
  3. Redirect a login con success
- **logout()**: `session()->flush()` → redirect a login

Rutas en `routes/web.php`:
```php
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout']);
```

Commit: `feat: implementar AuthController con login unificado (usuarios + gestoras)`

---

### TASK 8: Blade layouts y sistema de plantillas

**Rama git:** `feature/crud-migration`

1. **`resources/views/layouts/app.blade.php`**:
   - DOCTYPE html lang="es"
   - Meta viewport, charset UTF-8
   - Bootstrap 5.3 CDN, Google Fonts Inter, favicon
   - CSS custom inline o archivo (copiar estilo del P2: gradient navbar indigo, cards hover, filas coloreadas)
   - Navbar dinámica:
     - Si `session('user')` con rol admin → links: Dashboard, Incidencias, Calendario, Técnicos, Especialidades, **Gestoras**, **Liquidaciones**
     - Si rol tecnico → Mi agenda
     - Si rol particular → Dashboard, Mis avisos, Nueva incidencia
     - Si `session('gestora')` → Dashboard, Crear aviso, Liquidaciones
     - Derecha: nombre usuario + Perfil + Cerrar sesión
   - `<div class="container mt-4">@yield('content')</div>`
   - Bootstrap JS CDN

2. **`resources/views/layouts/auth.blade.php`**:
   - Fondo gradient (linear-gradient 135deg #1e1b4b → #4338ca)
   - Centrado vertical/horizontal
   - Container max-width 420px
   - `@yield('content')`

3. Copiar `public/assets/css/style.css` del P2 al nuevo proyecto en `public/css/style.css`
4. Copiar favicons a `public/`

Commit: `feat: crear layouts Blade con navbar dinámica por roles y estilos mejorados`


---

### TASK 9: Panel Cliente (migración completa)

**Rama git:** `feature/crud-migration`

Crear `app/Http/Controllers/ClienteController.php`:

- **dashboard()**: Renderiza `cliente.dashboard` con nombre del usuario
- **misAvisos()**: 
  ```php
  $avisos = Incidencia::where('cliente_id', session('user.id'))
      ->with(['estado', 'especialidad'])
      ->orderBy('fecha_servicio', 'desc')->get();
  return view('cliente.mis_avisos', compact('avisos'));
  ```
- **create()**: Carga especialidades → renderiza formulario
- **store(Request $request)**:
  1. Validar campos requeridos
  2. Normalizar tipo_urgencia (estandar/urgente)
  3. **Regla 48h**: si tipo=estandar y fecha_servicio < now+48h → error
  4. Asignar precio_base según especialidad_id (mapa de precios)
  5. Asignar zona_id (seleccionable en formulario)
  6. Generar localizador con `Incidencia::generarLocalizador()`
  7. Crear incidencia con Eloquent
  8. Redirect con success
- **cancel(Request $request)**:
  1. Buscar incidencia por id donde cliente_id = session user
  2. **Regla 48h**: si fecha_servicio < now+48h → no se puede cancelar
  3. Cambiar estado a "Cancelada"

Vistas Blade:
- `resources/views/cliente/dashboard.blade.php`
- `resources/views/cliente/mis_avisos.blade.php` (tabla con localizador, servicio, estado badge, fecha, dirección, urgencia badge, botón cancelar)
- `resources/views/cliente/nueva_incidencia.blade.php` (form: especialidad, urgencia, fecha, dirección, zona, descripción)

Rutas:
```php
Route::middleware(['authcheck', 'role:particular'])->prefix('cliente')->group(function () {
    Route::get('/dashboard', [ClienteController::class, 'dashboard']);
    Route::get('/mis-avisos', [ClienteController::class, 'misAvisos']);
    Route::get('/nueva-incidencia', [ClienteController::class, 'create']);
    Route::post('/nueva-incidencia', [ClienteController::class, 'store']);
    Route::post('/cancelar-incidencia', [ClienteController::class, 'cancel']);
});
```

Commit: `feat: migrar panel cliente completo (dashboard, avisos, crear/cancelar incidencia con regla 48h)`

---

### TASK 10: Panel Admin — Incidencias (migración completa)

**Rama git:** `feature/crud-migration`

Crear `app/Http/Controllers/AdminController.php`:

- **dashboard()**: Renderiza `admin.dashboard` con cards de acceso rápido
- **incidencias()**: 
  ```php
  $incidencias = Incidencia::with(['cliente', 'tecnico', 'estado', 'especialidad'])->orderBy('fecha_servicio', 'desc')->get();
  $tecnicos = Tecnico::all();
  return view('admin.incidencias', compact('incidencias', 'tecnicos'));
  ```
- **crearIncidencia()**: Carga clientes + especialidades + zonas → formulario
- **storeIncidencia(Request $request)**: Crear incidencia SIN restricción 48h (admin puede cualquier fecha). Asignar precio_base según especialidad.
- **editIncidencia($id)**: Carga incidencia + especialidades + estados → formulario edición
- **updateIncidencia(Request $request, $id)**: Actualizar campos. **IMPORTANTE**: Si el estado cambia a "Finalizada" y la incidencia tiene gestora_id → crear comisión automáticamente:
  ```php
  if ($nuevoEstado == 'Finalizada' && $incidencia->gestora_id) {
      Comision::create([
          'gestora_id' => $incidencia->gestora_id,
          'incidencia_id' => $incidencia->id,
          'monto_base' => $incidencia->precio_base,
          'porcentaje_aplicado' => $incidencia->gestora->porcentaje_comision,
          'monto_comision' => $incidencia->precio_base * ($incidencia->gestora->porcentaje_comision / 100),
          'mes' => now()->startOfMonth()->toDateString(),
      ]);
  }
  ```
- **asignarTecnico(Request $request)**: Asignar técnico + cambiar estado a "Asignada"
- **cancelarIncidencia(Request $request)**: Cambiar estado a "Cancelada"
- **calendario()**: Cargar todas las incidencias → vista con calendario JS

Vistas Blade:
- `admin/dashboard.blade.php`, `admin/incidencias.blade.php`, `admin/crear_incidencia.blade.php`, `admin/editar_incidencia.blade.php`, `admin/calendario.blade.php`

Rutas:
```php
Route::middleware(['authcheck', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/incidencias', [AdminController::class, 'incidencias']);
    Route::get('/crear-incidencia', [AdminController::class, 'crearIncidencia']);
    Route::post('/crear-incidencia', [AdminController::class, 'storeIncidencia']);
    Route::get('/editar-incidencia/{id}', [AdminController::class, 'editIncidencia']);
    Route::post('/actualizar-incidencia/{id}', [AdminController::class, 'updateIncidencia']);
    Route::post('/asignar-tecnico', [AdminController::class, 'asignarTecnico']);
    Route::post('/cancelar-incidencia', [AdminController::class, 'cancelarIncidencia']);
    Route::get('/calendario', [AdminController::class, 'calendario']);
});
```

Commit: `feat: migrar panel admin completo (CRUD incidencias, asignación técnicos, calendario)`

---

### TASK 11: CRUD Técnicos y Especialidades

**Rama git:** `feature/crud-migration`

**TecnicoController:**
- index(): Listar técnicos con especialidad → vista con tabla + formulario crear
- store(Request): Crear técnico (nombre_completo, especialidad_id, disponible)
- update(Request, $id): Actualizar especialidad y disponibilidad
- delete($id): Eliminar técnico
- agenda(): Para rol técnico — listar sus incidencias asignadas ordenadas por fecha

**EspecialidadController:**
- index(): Listar especialidades → vista con tabla editable inline
- store(Request): Crear especialidad
- update(Request, $id): Actualizar nombre
- delete($id): Eliminar especialidad

**UserController:**
- perfil(): Mostrar datos del usuario actual
- updatePerfil(Request): Actualizar nombre, email, teléfono, password (opcional)

Vistas Blade:
- `admin/tecnicos.blade.php`, `admin/especialidades.blade.php`
- `tecnico/agenda.blade.php`
- `user/perfil.blade.php`

Rutas:
```php
// Técnicos (admin)
Route::middleware(['authcheck', 'role:admin'])->group(function () {
    Route::get('/tecnicos', [TecnicoController::class, 'index']);
    Route::post('/tecnicos/guardar', [TecnicoController::class, 'store']);
    Route::post('/tecnicos/actualizar/{id}', [TecnicoController::class, 'update']);
    Route::post('/tecnicos/eliminar/{id}', [TecnicoController::class, 'delete']);
    Route::get('/especialidades', [EspecialidadController::class, 'index']);
    Route::post('/especialidades/guardar', [EspecialidadController::class, 'store']);
    Route::post('/especialidades/actualizar/{id}', [EspecialidadController::class, 'update']);
    Route::post('/especialidades/eliminar/{id}', [EspecialidadController::class, 'delete']);
});

// Técnico (su agenda)
Route::middleware(['authcheck', 'role:tecnico'])->group(function () {
    Route::get('/tecnico/agenda', [TecnicoController::class, 'agenda']);
});

// Perfil (todos los roles)
Route::middleware(['authcheck'])->group(function () {
    Route::get('/perfil', [UserController::class, 'perfil']);
    Route::post('/perfil', [UserController::class, 'updatePerfil']);
});
```

Commit: `feat: migrar CRUDs de técnicos, especialidades y perfil de usuario`

---

### TASK 12: Panel Gestora B2B

**Rama git:** `feature/b2b-gestoras`

Crear `app/Http/Controllers/GestoraController.php`:

- **dashboard()**:
  ```php
  $gestora = EmpresaGestora::find(session('gestora.id'));
  $serviciosMes = $gestora->incidencias()->whereMonth('created_at', now()->month)->count();
  $comisionesMes = $gestora->comisiones()->whereMonth('mes', now()->month)->sum('monto_comision');
  $totalPendiente = $gestora->comisiones()->where('pagada', false)->sum('monto_comision');
  return view('gestora.dashboard', compact('gestora', 'serviciosMes', 'comisionesMes', 'totalPendiente'));
  ```

- **crearAviso()**: Cargar especialidades + zonas → formulario
- **storeAviso(Request $request)**:
  1. Validar: especialidad_id, descripcion, direccion, fecha_servicio, tipo_urgencia, nombre_residente, zona_id
  2. Aplicar regla 48h (igual que clientes)
  3. Determinar precio_base según especialidad
  4. Crear incidencia con:
     - cliente_id = primer admin (o crear usuario genérico "vecino")
     - gestora_id = session('gestora.id')
     - nombre_residente = del formulario
     - zona_id = del formulario
  5. Generar localizador
  6. Redirect con success

- **liquidaciones()**:
  ```php
  $gestora = EmpresaGestora::find(session('gestora.id'));
  $liquidaciones = Comision::where('gestora_id', $gestora->id)
      ->selectRaw('DATE_FORMAT(mes, "%Y-%m") as periodo, COUNT(*) as num_servicios, SUM(monto_comision) as total_comision')
      ->groupBy('periodo')
      ->orderBy('periodo', 'desc')
      ->get();
  $totalPendiente = $gestora->comisiones()->where('pagada', false)->sum('monto_comision');
  return view('gestora.liquidaciones', compact('liquidaciones', 'totalPendiente'));
  ```

Vistas Blade:
- `gestora/dashboard.blade.php`: Cards con resumen (servicios mes, comisiones mes, total pendiente) + accesos rápidos
- `gestora/crear_aviso.blade.php`: Formulario (nombre_residente, especialidad, zona, dirección, fecha, urgencia, descripción)
- `gestora/liquidaciones.blade.php`: Tabla agrupada por mes (periodo, nº servicios, total comisión) + total acumulado pendiente

Rutas:
```php
Route::middleware(['gestora'])->prefix('gestora')->group(function () {
    Route::get('/dashboard', [GestoraController::class, 'dashboard']);
    Route::get('/crear-aviso', [GestoraController::class, 'crearAviso']);
    Route::post('/crear-aviso', [GestoraController::class, 'storeAviso']);
    Route::get('/liquidaciones', [GestoraController::class, 'liquidaciones']);
});
```

Commit: `feat: implementar panel B2B gestoras (dashboard, crear avisos por vecinos, liquidaciones mensuales)`


---

### TASK 13: Gestión de Comisiones (Panel Admin)

**Rama git:** `feature/b2b-gestoras`

Ampliar `AdminController.php` con métodos:

- **gestoras()**: Listar todas las gestoras (nombre, CIF, %, activa) + botón crear
- **crearGestora()**: Formulario alta gestora
- **storeGestora(Request $request)**:
  1. Validar: nombre, cif (unique), direccion, telefono, email (unique), password, porcentaje_comision
  2. Crear EmpresaGestora con password hasheada
  3. Redirect con success
- **editGestora($id)**: Formulario edición
- **updateGestora(Request $request, $id)**: Actualizar datos gestora
- **comisionesGestora($id)**:
  ```php
  $gestora = EmpresaGestora::findOrFail($id);
  $servicios = Incidencia::where('gestora_id', $id)->with(['estado', 'especialidad', 'comision'])->get();
  $totalPendiente = Comision::where('gestora_id', $id)->where('pagada', false)->sum('monto_comision');
  return view('admin.comisiones_gestora', compact('gestora', 'servicios', 'totalPendiente'));
  ```
- **liquidacionMensual()**:
  ```php
  $gestoras = EmpresaGestora::where('activa', true)->get();
  $resumen = [];
  foreach ($gestoras as $g) {
      $resumen[] = [
          'gestora' => $g,
          'total_mes' => Comision::where('gestora_id', $g->id)
              ->where('pagada', false)
              ->whereMonth('mes', now()->month)
              ->sum('monto_comision'),
          'total_pendiente' => Comision::where('gestora_id', $g->id)
              ->where('pagada', false)
              ->sum('monto_comision'),
      ];
  }
  $totalGlobal = Comision::where('pagada', false)->whereMonth('mes', now()->month)->sum('monto_comision');
  return view('admin.liquidacion_mensual', compact('resumen', 'totalGlobal'));
  ```

Vistas Blade:
- `admin/gestoras.blade.php`: Tabla de gestoras + botón crear
- `admin/crear_gestora.blade.php`: Formulario (nombre, CIF, dirección, teléfono, email, password, porcentaje)
- `admin/editar_gestora.blade.php`: Formulario edición
- `admin/comisiones_gestora.blade.php`: Servicios de una gestora con estado, precio, comisión + total pendiente
- `admin/liquidacion_mensual.blade.php`: Resumen global — cada gestora con total mes + total pendiente + TOTAL GLOBAL a pagar

Rutas (añadir al grupo admin):
```php
Route::get('/gestoras', [AdminController::class, 'gestoras']);
Route::get('/gestoras/crear', [AdminController::class, 'crearGestora']);
Route::post('/gestoras/crear', [AdminController::class, 'storeGestora']);
Route::get('/gestoras/editar/{id}', [AdminController::class, 'editGestora']);
Route::post('/gestoras/actualizar/{id}', [AdminController::class, 'updateGestora']);
Route::get('/gestoras/{id}/comisiones', [AdminController::class, 'comisionesGestora']);
Route::get('/liquidacion-mensual', [AdminController::class, 'liquidacionMensual']);
```

Commit: `feat: implementar gestión de comisiones en panel admin (alta gestoras, desglose, liquidación mensual)`

---

### TASK 14: API REST — Servicios por Zona

**Rama git:** `feature/api-rest`

Crear `app/Http/Controllers/ApiController.php`:

```php
class ApiController extends Controller
{
    public function serviciosPorZona()
    {
        $totalGlobal = Incidencia::whereHas('estado', fn($q) => $q->where('nombre_estado', 'Finalizada'))->count();

        if ($totalGlobal === 0) {
            return response()->json([], 200);
        }

        $zonas = Zona::withCount(['incidencias' => function ($query) {
            $query->whereHas('estado', fn($q) => $q->where('nombre_estado', 'Finalizada'));
        }])->get();

        $resultado = $zonas->map(function ($zona) use ($totalGlobal) {
            return [
                'nombre_zona' => $zona->nombre_zona,
                'total_servicios' => $zona->incidencias_count,
                'porcentaje' => round(($zona->incidencias_count / $totalGlobal) * 100, 2),
            ];
        });

        return response()->json($resultado, 200);
    }
}
```

Ruta en `routes/api.php`:
```php
Route::get('/servicios/zonas', [ApiController::class, 'serviciosPorZona']);
```

**IMPORTANTE**: Con el prefix configurado en RouteServiceProvider, la URL final será:
`https://fp064.techlab.uoc.edu/~uocx1/producto3/api/servicios/zonas`

Respuesta esperada:
```json
[
    {"nombre_zona": "Centro", "total_servicios": 5, "porcentaje": 25.00},
    {"nombre_zona": "Norte", "total_servicios": 4, "porcentaje": 20.00},
    {"nombre_zona": "Sur", "total_servicios": 3, "porcentaje": 15.00},
    {"nombre_zona": "Este", "total_servicios": 4, "porcentaje": 20.00},
    {"nombre_zona": "Oeste", "total_servicios": 2, "porcentaje": 10.00},
    {"nombre_zona": "Ensanche", "total_servicios": 2, "porcentaje": 10.00}
]
```

Commit: `feat: crear API REST GET /api/servicios/zonas con JSON agregado por zona`

---

### TASK 15: Despliegue en Servidor UOC

**Rama git:** `feature/deploy`

#### Estructura en servidor:
```
~/public_html/producto3/              ← .htaccess que redirige a public/
~/public_html/producto3/public/       ← DocumentRoot real de Laravel
~/public_html/producto3/app/
~/public_html/producto3/config/
~/public_html/producto3/database/
~/public_html/producto3/resources/
~/public_html/producto3/routes/
~/public_html/producto3/storage/
~/public_html/producto3/vendor/
~/public_html/producto3/.env
~/public_html/producto3/artisan
~/public_html/producto3/composer.json
```

#### .htaccess en ~/public_html/producto3/ (raíz del proyecto):
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

#### .env de producción:
```
APP_NAME=ReparaYa
APP_ENV=production
APP_KEY=  (generar con php artisan key:generate)
APP_DEBUG=false
APP_URL=https://fp064.techlab.uoc.edu/~uocx1/producto3

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=reparaya
DB_USERNAME=wordpress1
DB_PASSWORD=DWD8Ds3l4dvXpjZH

SESSION_DRIVER=file
```

#### Pasos de despliegue (comandos):
```bash
# 1. Conectar al servidor
ssh uocx1@fp064.techlab.uoc.edu -p 55000

# 2. Ir a la carpeta
cd ~/public_html/producto3

# 3. Subir archivos via SFTP o git clone
# Opción A: SFTP
sftp -P 55000 uocx1@fp064.techlab.uoc.edu
put -r ./producto3/* ~/public_html/producto3/

# Opción B: Git (si el servidor tiene git)
git clone <repo-url> ~/public_html/producto3

# 4. Instalar dependencias
cd ~/public_html/producto3
composer install --no-dev --optimize-autoloader

# 5. Configurar
cp .env.example .env
# Editar .env con datos de producción (ver arriba)
php artisan key:generate

# 6. Migraciones y seeders
php artisan migrate --force
php artisan db:seed --force

# 7. Optimizar
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Permisos
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 9. Verificar
curl https://fp064.techlab.uoc.edu/~uocx1/producto3/login
curl https://fp064.techlab.uoc.edu/~uocx1/producto3/api/servicios/zonas
```

#### Solución si no hay acceso a composer en servidor:
1. Ejecutar `composer install --no-dev` en local
2. Subir la carpeta `vendor/` completa via SFTP

Commit: `deploy: configurar .htaccess, .env producción y documentar proceso de despliegue`

---

### TASK 16: Control de Versiones Git

**Rama git:** Todas las anteriores → merge a `main`

#### Estrategia de ramas:
```
main
├── feature/laravel-init        (Tasks 1-4: proyecto + migraciones + seeders)
├── feature/auth-middleware     (Tasks 6-7: auth + middleware)
├── feature/crud-migration      (Tasks 5, 8-11: modelos + blade + CRUDs)
├── feature/b2b-gestoras        (Tasks 12-13: panel gestora + comisiones admin)
├── feature/api-rest            (Task 14: endpoint API)
└── feature/deploy              (Task 15: despliegue)
```

#### Flujo:
1. Crear rama: `git checkout -b feature/laravel-init`
2. Trabajar con commits descriptivos
3. Merge a main: `git checkout main && git merge --no-ff feature/laravel-init`
4. Repetir para cada rama

#### Commits ejemplo (mensajes descriptivos):
```
feat: inicializar proyecto Laravel 10 con configuración base /producto3
feat: crear migraciones para tablas existentes (usuarios, incidencias, tecnicos, especialidades, estados, zonas)
feat: crear migraciones B2B (empresas_gestoras, comisiones)
feat: crear seeders con datos realistas
feat: crear modelos Eloquent con relaciones
feat: implementar middlewares de autenticación y roles
feat: implementar AuthController con login unificado
feat: crear layouts Blade con navbar dinámica
feat: migrar panel cliente (dashboard, avisos, nueva incidencia)
feat: migrar panel admin (CRUD incidencias, asignación técnicos, calendario)
feat: migrar CRUDs técnicos y especialidades
feat: implementar panel B2B gestoras (crear avisos, liquidaciones)
feat: implementar gestión comisiones en admin (alta gestoras, liquidación mensual)
feat: crear API REST /api/servicios/zonas
fix: corregir regla 48h en cancelación
deploy: configurar despliegue servidor UOC
```

---

### TASK 17: Preparación para Defensa Técnica

**No es código — es preparación para el vídeo individual de 2 min**

Puntos clave a demostrar en el vídeo (para 6/6 pts):

1. **Rutas**: Mostrar `routes/web.php` y explicar:
   - Grupos de rutas con prefix y middleware
   - Diferencia entre `routes/web.php` (sesiones) y `routes/api.php` (stateless, JSON)
   - Named routes y cómo se usan en Blade con `route('nombre')`

2. **Eloquent ORM**: Mostrar un modelo y explicar:
   - Relaciones: `belongsTo`, `hasMany`, `hasOne`
   - Eager loading con `with()` para evitar N+1
   - Scopes: `scopeFinalizadas()`
   - Mass assignment con `$fillable`
   - Casts: `$casts = ['fecha_servicio' => 'datetime']`

3. **Middlewares**: Mostrar `RoleMiddleware.php` y explicar:
   - Cómo intercepta la request antes del controlador
   - Cómo se registra en Kernel.php
   - Cómo se aplica en rutas: `->middleware(['authcheck', 'role:admin'])`
   - Diferencia con el P2 donde se hacía `if ($_SESSION['user']['rol'] !== 'admin') die()`

4. **Ejemplo de flujo completo**: Mostrar cómo una request a `/producto3/admin/incidencias`:
   - Pasa por middleware auth → verifica sesión
   - Pasa por middleware role:admin → verifica rol
   - Llega a AdminController@incidencias
   - Usa Eloquent con eager loading
   - Renderiza vista Blade con `@extends`, `@section`, `@foreach`

---

## Notas Adicionales

### Para el criterio de IA Generativa (4 pts):
Documentar 4 prompts avanzados usados durante el desarrollo. Ejemplo:
1. "Genera las migraciones Laravel para migrar esta estructura SQL existente preservando relaciones FK..."
2. "Implementa un middleware Laravel que verifique roles múltiples pasados como parámetros..."
3. "Crea un endpoint API que agrupe incidencias por zona calculando porcentajes con Eloquent..."
4. "Configura el RouteServiceProvider para que toda la app funcione bajo el subdirectorio /producto3..."

**IMPORTANTE**: El mapa conceptual debe ser A MANO (dibujado, no generado). Debe mostrar la arquitectura Laravel del proyecto: Request → Router → Middleware → Controller → Model/Eloquent → View/Blade.

### Checklist final antes de entregar:
- [ ] `php artisan migrate:fresh --seed` funciona sin errores
- [ ] Login funciona para admin, técnico, cliente y gestora
- [ ] Todos los CRUDs del P2 funcionan en Laravel
- [ ] Gestora puede crear avisos y ver liquidaciones
- [ ] Admin puede dar de alta gestoras y ver liquidación mensual
- [ ] API `/api/servicios/zonas` devuelve JSON correcto
- [ ] App accesible en `https://fp064.techlab.uoc.edu/~uocx1/producto3`
- [ ] Git tiene historial con ramas y commits descriptivos
- [ ] Assets (CSS, JS, imágenes) cargan correctamente en producción
