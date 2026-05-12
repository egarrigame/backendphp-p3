# Implementation Plan: Laravel Migration B2B

## Overview

Migration of the existing ReparaYa PHP MVC application to Laravel 10, preserving all current functionality while adding B2B capabilities (Empresas Gestoras, commissions, REST API). The implementation follows a sequential approach: project setup → database → models → auth → views → panels → API → deployment.

## Tasks

- [x] 1. Initialize Laravel 10 project
  - [x] 1.1 Create Laravel 10 project in the repository root (replace current PHP MVC structure)
    - Configure `.env` with DB credentials (DB_DATABASE=reparaya, DB_USERNAME=root, DB_PASSWORD=1234 for local)
    - Configure `RouteServiceProvider` to prefix all web routes with `/producto3` and api routes with `/producto3/api`
    - Set APP_URL=http://localhost:8000/producto3
    - Configure asset_url in config/app.php for subdirectory support
    - Create proper .gitignore (vendor/, node_modules/, .env, storage/logs/, .DS_Store)
    - _Requirements: 9.2, 9.4, 9.5_

- [x] 2. Create migrations for existing tables
  - [x] 2.1 Create migration `create_usuarios_table`
    - Columns: id, nombre(100), email(100 unique), password(255), rol enum[admin,tecnico,particular] default particular, telefono(20 nullable), timestamps
    - _Requirements: 1.1_
  - [x] 2.2 Create migration `create_especialidades_table`
    - Columns: id, nombre_especialidad(50), timestamps
    - _Requirements: 1.1_
  - [x] 2.3 Create migration `create_estados_table`
    - Columns: id, nombre_estado(50), timestamps
    - _Requirements: 1.1_
  - [x] 2.4 Create migration `create_zonas_table`
    - Columns: id, nombre_zona(100), timestamps
    - _Requirements: 1.4_
  - [x] 2.5 Create migration `create_tecnicos_table`
    - Columns: id, usuario_id(FK usuarios nullable unique, nullOnDelete), nombre_completo(100), especialidad_id(FK especialidades), disponible(boolean default true), timestamps
    - _Requirements: 1.1_
  - [x] 2.6 Create migration `create_incidencias_table`
    - Columns: id, localizador(12 unique), cliente_id(FK usuarios), tecnico_id(FK tecnicos nullable), especialidad_id(FK especialidades), estado_id(FK estados default 1), zona_id(FK zonas nullable), descripcion(text), direccion(255), fecha_servicio(datetime), tipo_urgencia enum[estandar,urgente] default estandar, precio_base(decimal 8,2 default 0), timestamps
    - Add index on cliente_id and tecnico_id
    - _Requirements: 1.1, 1.5, 1.6_

- [x] 3. Create migrations for B2B tables
  - [x] 3.1 Create migration `create_empresas_gestoras_table`
    - Columns: id, nombre(150), cif(20 unique), direccion(255), telefono(20), email(100 unique), password(255), porcentaje_comision(decimal 5,2 default 10.00), activa(boolean default true), timestamps
    - _Requirements: 1.2_
  - [x] 3.2 Create migration `add_gestora_fields_to_incidencias_table`
    - Add gestora_id(FK empresas_gestoras nullable), nombre_residente(100 nullable) after zona_id
    - _Requirements: 1.6_
  - [x] 3.3 Create migration `create_comisiones_table`
    - Columns: id, gestora_id(FK empresas_gestoras), incidencia_id(FK incidencias), monto_base(decimal 8,2), porcentaje_aplicado(decimal 5,2), monto_comision(decimal 8,2), mes(date), pagada(boolean default false), timestamps
    - Add unique composite index on (gestora_id, incidencia_id, mes)
    - _Requirements: 1.3, 1.9_

- [x] 4. Create Seeders with realistic data
  - [x] 4.1 Create EstadoSeeder, EspecialidadSeeder, ZonaSeeder
    - EstadoSeeder: Pendiente, Asignada, Finalizada, Cancelada
    - EspecialidadSeeder: Fontanería, Electricidad, Aire acondicionado, Bricolaje, Cerrajería, Pintura
    - ZonaSeeder: Centro, Norte, Sur, Este, Oeste, Ensanche
    - _Requirements: 1.8_
  - [x] 4.2 Create UserSeeder and TecnicoSeeder
    - UserSeeder: admin (root@uoc.edu/123456/admin), tecnico (tecnico@uoc.com/123456/tecnico), cliente (cliente@uoc.com/123456/particular), plus 3 additional clients with realistic Spanish names
    - TecnicoSeeder: link tecnico user to Fontanería + create 2 more technicians with different specialties
    - _Requirements: 1.8_
  - [x] 4.3 Create GestoraSeeder
    - "Fincas López" (CIF B12345678, fincas@lopez.com, 123456, 10%), "Gestiones Martínez" (CIF B87654321, gestiones@martinez.com, 123456, 5%)
    - _Requirements: 1.8_
  - [x] 4.4 Create IncidenciaSeeder and ComisionSeeder
    - IncidenciaSeeder: 20 incidencias distributed across zones and clients, some with gestora_id, precio_base per specialty (Fontanería=80, Electricidad=65, Aire acondicionado=120, Bricolaje=45, Cerrajería=90, Pintura=70), mix of states including several Finalizada
    - ComisionSeeder: create comision records for all Finalizada incidencias that have gestora_id (monto_comision = precio_base * porcentaje/100)
    - _Requirements: 1.8_
  - [x] 4.5 Create DatabaseSeeder calling all seeders in correct order
    - Order: EstadoSeeder, EspecialidadSeeder, ZonaSeeder, UserSeeder, TecnicoSeeder, GestoraSeeder, IncidenciaSeeder, ComisionSeeder
    - _Requirements: 1.8, 1.11_

- [x] 5. Checkpoint - Verify database layer
  - Ensure `php artisan migrate:fresh --seed` runs without errors. Ask the user if questions arise.

- [x] 6. Create Eloquent Models with relationships
  - [x] 6.1 Create User, Especialidad, Estado, and Zona models
    - User model (table: usuarios): fillable[nombre,email,password,rol,telefono], hidden[password], relations: hasMany(Incidencia,'cliente_id'), hasOne(Tecnico,'usuario_id')
    - Especialidad model (table: especialidades): fillable[nombre_especialidad], relations: hasMany(Tecnico), hasMany(Incidencia)
    - Estado model (table: estados): fillable[nombre_estado], relations: hasMany(Incidencia)
    - Zona model (table: zonas): fillable[nombre_zona], relations: hasMany(Incidencia)
    - _Requirements: 2.3_
  - [x] 6.2 Create Tecnico model
    - Table: tecnicos, fillable[usuario_id,nombre_completo,especialidad_id,disponible], casts[disponible=>boolean]
    - Relations: belongsTo(User,'usuario_id'), belongsTo(Especialidad), hasMany(Incidencia)
    - _Requirements: 2.3_
  - [x] 6.3 Create Incidencia model
    - Table: incidencias, fillable[localizador,cliente_id,tecnico_id,especialidad_id,estado_id,zona_id,gestora_id,nombre_residente,descripcion,direccion,fecha_servicio,tipo_urgencia,precio_base]
    - Casts[fecha_servicio=>datetime,precio_base=>decimal:2]
    - Relations: belongsTo(User,'cliente_id'), belongsTo(Tecnico), belongsTo(Especialidad), belongsTo(Estado), belongsTo(Zona), belongsTo(EmpresaGestora,'gestora_id'), hasOne(Comision)
    - Static method generarLocalizador(): 'R'.strtoupper(substr(uniqid(),-6))
    - Scope scopeFinalizadas
    - _Requirements: 2.3, 2.10_
  - [x] 6.4 Create EmpresaGestora and Comision models
    - EmpresaGestora model (table: empresas_gestoras): fillable[nombre,cif,direccion,telefono,email,password,porcentaje_comision,activa], hidden[password], casts[porcentaje_comision=>decimal:2,activa=>boolean], relations: hasMany(Incidencia,'gestora_id'), hasMany(Comision,'gestora_id')
    - Comision model (table: comisiones): fillable[gestora_id,incidencia_id,monto_base,porcentaje_aplicado,monto_comision,mes,pagada], casts[mes=>date,pagada=>boolean,monto_base=>decimal:2,monto_comision=>decimal:2], relations: belongsTo(EmpresaGestora,'gestora_id'), belongsTo(Incidencia)
    - _Requirements: 2.3_

- [x] 7. Create Authentication and Role Middlewares
  - [x] 7.1 Create AuthCheck, RoleMiddleware, and GestoraMiddleware
    - AuthCheck.php: check session('user') OR session('gestora') exists, else redirect to /producto3/login
    - RoleMiddleware.php: receive roles as variadic params, check session('user.rol') is in allowed roles, else abort(403)
    - GestoraMiddleware.php: check session('gestora') exists, else redirect to /producto3/login
    - Register all three in Kernel.php $middlewareAliases as 'authcheck', 'role', 'gestora'
    - _Requirements: 2.6, 3.7, 3.8_

- [x] 8. Implement AuthController (Login/Register/Logout)
  - [x] 8.1 Create AuthController with methods: showLogin, login, showRegister, register, logout
    - showLogin: if session exists redirect by role, else render auth.login view
    - login: validate email+password required. Search usuarios table by email. If not found, search empresas_gestoras by email. Verify password with password_verify(). If usuario: store session('user') = {id,nombre,email,rol}, redirect by rol. If gestora: store session('gestora') = {id,nombre,email}, redirect to gestora/dashboard. If fail: redirect back with error.
    - register: validate nombre, email(unique:usuarios), telefono, password(min:4). Create user with rol=particular, hashed password. Redirect to login with success.
    - logout: session()->flush(), redirect to login
    - _Requirements: 2.2, 2.12, 3.4, 3.5, 3.6_
  - [x] 8.2 Create Blade views for auth (login.blade.php, register.blade.php) and define routes
    - Define routes: GET/POST /login, GET/POST /register, GET /logout
    - Views use layouts.auth
    - _Requirements: 2.1, 2.4_

- [x] 9. Create Blade Layouts and CSS
  - [x] 9.1 Create layouts and static assets
    - Create resources/views/layouts/app.blade.php: HTML5, Bootstrap 5.3 CDN, Google Fonts Inter, custom CSS, dynamic navbar showing links based on session role (admin sees Gestoras+Liquidaciones links, gestora sees its panel links), @yield('content')
    - Create resources/views/layouts/auth.blade.php: gradient background (#1e1b4b→#4338ca), centered container max-width 420px, @yield('content')
    - Copy CSS from P2 to public/css/style.css (adapt asset paths for /producto3)
    - Copy favicon files to public/
    - Ensure all asset references use asset() helper which respects the configured asset_url
    - _Requirements: 2.4, 2.5, 2.16, 9.5_

- [x] 10. Checkpoint - Verify auth and layouts
  - Ensure login/register/logout work for all user types and layouts render correctly. Ask the user if questions arise.

- [x] 11. Migrate Client Panel (ClienteController)
  - [x] 11.1 Create ClienteController with methods: dashboard, misAvisos, create, store, cancel
    - dashboard: render cliente.dashboard with user name
    - misAvisos: query Incidencia::where('cliente_id', session('user.id'))->with(['estado','especialidad'])->orderBy('fecha_servicio','desc')->get()
    - create: load especialidades + zonas, render form
    - store: validate fields, normalize tipo_urgencia, apply 48h rule for estandar (reject if fecha_servicio < now+48h), set precio_base by especialidad, generate localizador, create incidencia
    - cancel: find incidencia by id where cliente_id matches session, apply 48h rule, change estado to Cancelada
    - _Requirements: 2.1, 2.7, 2.9, 2.10, 2.11, 2.14, 2.15_
  - [x] 11.2 Create Blade views for client panel
    - cliente/dashboard.blade.php, cliente/mis_avisos.blade.php (table with badges), cliente/nueva_incidencia.blade.php (form with especialidad, urgencia, fecha, dirección, zona, descripción)
    - Define routes with middleware authcheck+role:particular under prefix 'cliente'
    - _Requirements: 2.1, 2.4_

- [x] 12. Migrate Admin Panel - Incidencias
  - [x] 12.1 Create AdminController with incidencia management methods
    - dashboard: render admin.dashboard with quick-access cards
    - incidencias: load all incidencias with relations (cliente, tecnico, estado, especialidad) + all tecnicos, render table
    - crearIncidencia: load clientes + especialidades + zonas, render form
    - storeIncidencia: NO 48h restriction for admin, set precio_base by especialidad, generate localizador, create
    - editIncidencia($id): load incidencia + especialidades + estados, render edit form
    - updateIncidencia: update fields. CRITICAL: if estado changes to Finalizada AND incidencia has gestora_id → auto-create Comision record (monto_comision = precio_base × gestora.porcentaje_comision / 100, mes = now()->startOfMonth())
    - asignarTecnico: assign tecnico_id + change estado to Asignada
    - cancelarIncidencia: change estado to Cancelada
    - calendario: load all incidencias, render calendar view with JS
    - _Requirements: 2.1, 2.2, 2.8, 2.13, 2.14, 5.1, 5.2, 5.3, 5.4, 5.5, 5.6, 5.7, 5.8_
  - [x] 12.2 Create Blade views for admin incidencia management
    - admin/dashboard.blade.php, admin/incidencias.blade.php, admin/crear_incidencia.blade.php, admin/editar_incidencia.blade.php, admin/calendario.blade.php
    - Define routes with middleware authcheck+role:admin under prefix 'admin'
    - _Requirements: 2.1, 2.4_

- [x] 13. Migrate Técnicos, Especialidades, and Perfil
  - [x] 13.1 Create TecnicoController and EspecialidadController
    - TecnicoController: index (list with create form), store, update($id), delete($id), agenda
    - agenda: for tecnico role — load incidencias where tecnico_id matches the tecnico linked to session user, ordered by fecha_servicio
    - EspecialidadController: index (list with inline edit), store, update($id), delete($id)
    - _Requirements: 2.1, 2.2_
  - [x] 13.2 Create UserController and related views
    - UserController: perfil (show user data), updatePerfil (update nombre, email, telefono, optional password)
    - Create Blade views: admin/tecnicos.blade.php, admin/especialidades.blade.php, tecnico/agenda.blade.php, user/perfil.blade.php
    - Define routes: tecnicos+especialidades under admin middleware, tecnico/agenda under tecnico middleware, perfil under authcheck
    - _Requirements: 2.1, 2.2, 2.4_

- [x] 14. Checkpoint - Verify core panels
  - Ensure all CRUDs work, tecnico sees their agenda, user can update profile, admin can manage incidencias with commission auto-calculation. Ask the user if questions arise.

- [x] 15. Implement Gestora B2B Panel
  - [x] 15.1 Create GestoraController with methods: dashboard, crearAviso, storeAviso, liquidaciones
    - dashboard: load gestora from session, show serviciosMes (count incidencias this month), comisionesMes (sum comisiones this month), totalPendiente (sum unpaid comisiones)
    - crearAviso: load especialidades + zonas, render form with fields: nombre_residente, especialidad, zona, dirección, fecha, urgencia, descripción
    - storeAviso: validate, apply 48h rule for estandar, set precio_base by especialidad, create incidencia with gestora_id=session('gestora.id') and nombre_residente, generate localizador, set cliente_id to admin user (id=1)
    - liquidaciones: query comisiones grouped by month (DATE_FORMAT(mes,'%Y-%m')), show periodo, num_servicios, total_comision per month + totalPendiente
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5, 4.6, 4.7, 6.1, 6.2, 6.3, 6.4, 6.5_
  - [x] 15.2 Create Blade views for gestora panel
    - gestora/dashboard.blade.php, gestora/crear_aviso.blade.php, gestora/liquidaciones.blade.php
    - Define routes with middleware 'gestora' under prefix 'gestora'
    - _Requirements: 2.4, 3.7_

- [x] 16. Implement Admin Commission Management
  - [x] 16.1 Add gestora management methods to AdminController
    - gestoras: list all EmpresaGestora (nombre, CIF, porcentaje, activa) + create button
    - crearGestora: form (nombre, CIF, dirección, teléfono, email, password, porcentaje_comision)
    - storeGestora: validate (cif unique, email unique), create with hashed password
    - editGestora/updateGestora: edit gestora data
    - comisionesGestora($id): show all incidencias for that gestora with estado, precio_base, comision amount + total pendiente
    - liquidacionMensual: for each active gestora show total_mes (unpaid comisiones this month) and total_pendiente (all unpaid), plus totalGlobal across all gestoras
    - _Requirements: 3.1, 3.2, 3.3, 7.1, 7.2, 7.3, 7.4, 7.5, 7.6, 7.7, 7.8, 7.9, 7.10_
  - [x] 16.2 Create Blade views for admin gestora management
    - admin/gestoras.blade.php, admin/crear_gestora.blade.php, admin/editar_gestora.blade.php, admin/comisiones_gestora.blade.php, admin/liquidacion_mensual.blade.php
    - Add routes to admin group
    - _Requirements: 2.1, 2.4_

- [x] 17. Implement REST API Endpoint
  - [x] 17.1 Create ApiController with method serviciosPorZona
    - Logic: count total Finalizada incidencias globally. If 0, return empty array []. Otherwise, for each zona count Finalizada incidencias, calculate porcentaje = (zona_count/total)*100 rounded to 2 decimals. Return JSON array with objects {nombre_zona, total_servicios, porcentaje}.
    - Define route in routes/api.php: Route::get('/servicios/zonas', [ApiController::class, 'serviciosPorZona'])
    - Ensure response has Content-Type: application/json and HTTP 200
    - Final URL: /producto3/api/servicios/zonas
    - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5, 8.6, 8.7, 8.8, 8.9, 8.10_

- [x] 18. Checkpoint - Verify B2B and API
  - Ensure gestora can login, create avisos, view dashboard stats, view monthly liquidaciones. Verify API returns valid JSON with correct structure. Ask the user if questions arise.

- [x] 19. Configure Deployment to UOC Server
  - [x] 19.1 Create deployment configuration files
    - Create .htaccess in project root: RewriteEngine On, RewriteRule ^(.*)$ public/$1 [L]
    - Create .env.production template with: APP_ENV=production, APP_DEBUG=false, APP_URL=https://fp064.techlab.uoc.edu/~uocx1/producto3, DB_HOST=localhost, DB_DATABASE=reparaya, DB_USERNAME=wordpress1, DB_PASSWORD=DWD8Ds3l4dvXpjZH, SESSION_DRIVER=file
    - _Requirements: 9.1, 9.2, 9.3_
  - [x] 19.2 Document deployment steps in README
    - Upload via SFTP (port 55000), composer install --no-dev, php artisan key:generate, php artisan migrate --force, php artisan db:seed --force, php artisan config:cache, php artisan route:cache, chmod -R 775 storage bootstrap/cache
    - If composer not available on server: run composer install locally and upload vendor/ folder
    - Verify app responds at https://fp064.techlab.uoc.edu/~uocx1/producto3/login
    - Verify API at https://fp064.techlab.uoc.edu/~uocx1/producto3/api/servicios/zonas
    - _Requirements: 9.1, 9.2, 9.6, 9.7, 9.8_

- [x] 20. Final checkpoint - Ensure all tests pass
  - Ensure all routes work, assets load correctly, login works for all user types (admin, tecnico, cliente, gestora). Ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional and can be skipped for faster MVP (none in this plan — all tasks are required for the rubric)
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation
- The implementation language is PHP (Laravel 10) as specified in the design document
- Commission auto-calculation is triggered in AdminController@updateIncidencia when estado changes to Finalizada
- All routes are prefixed with /producto3 for UOC server deployment
- Precio base is assigned automatically based on especialidad using the defined price map

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1.1"] },
    { "id": 1, "tasks": ["2.1", "2.2", "2.3", "2.4"] },
    { "id": 2, "tasks": ["2.5", "2.6", "3.1"] },
    { "id": 3, "tasks": ["3.2", "3.3"] },
    { "id": 4, "tasks": ["4.1", "4.2", "4.3"] },
    { "id": 5, "tasks": ["4.4", "4.5"] },
    { "id": 6, "tasks": ["6.1", "6.2"] },
    { "id": 7, "tasks": ["6.3", "6.4"] },
    { "id": 8, "tasks": ["7.1"] },
    { "id": 9, "tasks": ["8.1", "9.1"] },
    { "id": 10, "tasks": ["8.2"] },
    { "id": 11, "tasks": ["11.1", "12.1", "13.1"] },
    { "id": 12, "tasks": ["11.2", "12.2", "13.2"] },
    { "id": 13, "tasks": ["15.1", "16.1"] },
    { "id": 14, "tasks": ["15.2", "16.2"] },
    { "id": 15, "tasks": ["17.1"] },
    { "id": 16, "tasks": ["19.1", "19.2"] }
  ]
}
```
