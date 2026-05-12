# Requirements Document

## Introduction

Migration of the existing ReparaYa PHP MVC application to the Laravel framework, preserving all current functionality (user authentication, repair request management, technician assignment, specialties management) while adding new B2B capabilities. The B2B module introduces Property Management Companies (Empresas Gestoras) that can create repair requests on behalf of residents and earn commissions on completed services. A REST API endpoint provides aggregated service data by zone.

## Glossary

- **System**: The ReparaYa Laravel application as a whole
- **Auth_Module**: The authentication subsystem handling login, registration, and session management
- **Incidencia_Manager**: The subsystem responsible for creating, updating, assigning, and cancelling repair requests (incidencias/avisos)
- **Gestora_Module**: The subsystem managing Property Management Companies (Empresas Gestoras) and their B2B operations
- **Commission_Engine**: The subsystem that calculates and tracks commissions owed to Gestoras
- **API_Service**: The REST API subsystem that exposes JSON endpoints
- **Admin**: A user with the role "admin" who manages the entire platform
- **Cliente**: A user with the role "particular" (residential client) who creates repair requests
- **Tecnico**: A user with the role "tecnico" (technician) who is assigned to repair requests
- **Gestora**: A Property Management Company (Administrador de Fincas) registered by the Admin that can create repair requests on behalf of residents and earns commissions
- **Incidencia**: A repair request/service ticket with a unique localizador code
- **Especialidad**: A service specialty category (e.g., Fontanería, Electricidad)
- **Comision**: A commission record linking a completed service to a Gestora with a calculated amount
- **Liquidacion**: A monthly settlement summary of commissions owed to a Gestora
- **Zona**: A city zone to which a community of owners (comunidad de propietarios) is assigned
- **precio_base**: The base price of a service determined by the especialidad category

## Requirements

### Requirement 1: Laravel Database Migration [1 pt rúbrica]

**User Story:** As a developer, I want to migrate the existing database schema to Laravel Migrations, so that the database structure is version-controlled and reproducible.

#### Acceptance Criteria

1. THE System SHALL define Laravel Migration files for the tables: usuarios, especialidades, estados, tecnicos, and incidencias, preserving all columns, data types (including ENUM fields as ENUM columns), constraints (NOT NULL, UNIQUE, DEFAULT values), foreign keys with their ON DELETE behavior (SET NULL for tecnicos.usuario_id, RESTRICT for tecnicos.especialidad_id, incidencias.cliente_id, incidencias.especialidad_id, and incidencias.estado_id, and NO ACTION for incidencias.tecnico_id), and indexes (idx_cliente, idx_tecnico) exactly as defined in the existing bbddReparaYa.sql schema
2. THE System SHALL define a Laravel Migration file for a new "empresas_gestoras" table containing: id (auto-increment primary key), nombre (string, max 150 characters, NOT NULL), CIF (string, max 20 characters, NOT NULL, UNIQUE), direccion (string, max 255 characters), telefono (string, max 20 characters), email (string, max 100 characters, NOT NULL, UNIQUE), password (string, max 255 characters, NOT NULL), porcentaje_comision (decimal with precision 5 and scale 2, NOT NULL, default 10.00), activa (boolean, default true), and timestamps (created_at, updated_at)
3. THE System SHALL define a Laravel Migration file for a new "comisiones" table containing: id (auto-increment primary key), gestora_id (unsigned integer, NOT NULL, foreign key to empresas_gestoras.id with ON DELETE CASCADE), incidencia_id (unsigned integer, NOT NULL, foreign key to incidencias.id with ON DELETE CASCADE), monto_base (decimal with precision 8 and scale 2, NOT NULL), porcentaje_aplicado (decimal with precision 5 and scale 2, NOT NULL), monto_comision (decimal with precision 8 and scale 2, NOT NULL), mes (date storing the first day of the month for grouping, NOT NULL), pagada (boolean, default false), and timestamps (created_at, updated_at)
4. THE System SHALL define a Laravel Migration file for a new "zonas" table containing: id (auto-increment primary key), nombre_zona (string, max 100 characters, NOT NULL), and timestamps (created_at, updated_at)
5. THE System SHALL add a nullable zona_id foreign key column (unsigned integer, foreign key to zonas.id with ON DELETE SET NULL) to the incidencias table via a separate migration to associate each service with a city zone
6. THE System SHALL add the following columns to the incidencias table: precio_base (decimal with precision 8 and scale 2, default 0) to store the base price of the service, gestora_id (unsigned integer, nullable, foreign key to empresas_gestoras.id) to link incidencias created by a Gestora, and nombre_residente (string, max 100 characters, nullable) to store the resident name when a Gestora creates the request
7. WHEN migrations are executed via "php artisan migrate", THE System SHALL create all tables in dependency order (independent tables first, then dependent tables) using migration file timestamps that enforce the correct sequence, with correct foreign key relationships and indexes, completing with exit code 0 and no output to stderr
8. THE System SHALL include Laravel Seeder files that populate initial data for estados (Pendiente, Asignada, Finalizada, Cancelada), especialidades (Fontanería, Electricidad, Aire acondicionado, Bricolaje, Cerrajería, Pintura), zonas (Centro, Norte, Sur, Este, Oeste, Ensanche), empresas_gestoras ("Fincas López" with CIF=B12345678, email=fincas@lopez.com, porcentaje_comision=10, and "Gestiones Martínez" with CIF=B87654321, email=gestiones@martinez.com, porcentaje_comision=5), 20 incidencias with realistic prices distributed across different especialidades and zonas, comisiones pre-generated for finalized incidencias that have a gestora_id, and the following test users: admin (nombre: "Admin", email: "root@uoc.edu", rol: "admin", password: "123456"), técnico (nombre: "Técnico", email: "tecnico@uoc.com", rol: "tecnico", password: "123456"), and cliente (nombre: "Cliente", email: "cliente@uoc.com", rol: "particular", password: "123456"), all with passwords hashed using Laravel's Hash facade so that seeded credentials are verifiable in testing
9. THE System SHALL define a unique composite index on the comisiones table for the combination of gestora_id, incidencia_id, and mes to prevent duplicate commission entries for the same service in the same month
10. WHEN "php artisan migrate:rollback" is executed, THE System SHALL reverse all migrations by dropping tables in reverse dependency order (dependent tables first, then independent tables), completing with exit code 0 and no output to stderr
11. WHEN "php artisan migrate:fresh --seed" is executed, THE System SHALL drop all tables, re-run all migrations from scratch, and execute all seeders, completing with exit code 0 and no errors, resulting in a fully populated database ready for testing

### Requirement 2: Laravel MVC Architecture [8 pts rúbrica]

**User Story:** As a developer, I want the application fully migrated to Laravel conventions, so that the codebase uses Eloquent ORM, Blade templates, and Laravel routing.

#### Acceptance Criteria

1. THE System SHALL define Laravel Routes in web.php covering all existing endpoints: authentication (login GET/POST, register GET/POST, logout GET), client routes (dashboard, mis-avisos, nueva-incidencia GET/POST, cancelar-incidencia POST), admin routes (dashboard, calendario, incidencias, asignar-tecnico POST, cancelar-incidencia POST, crear-incidencia GET/POST, editar-incidencia GET, actualizar-incidencia POST, gestoras GET, gestoras/crear GET/POST, gestoras/editar/{id} GET, gestoras/actualizar/{id} POST, gestoras/{id}/comisiones GET, liquidacion-mensual GET), technician routes (agenda), technician CRUD routes (index GET, guardar POST, actualizar POST, eliminar POST), especialidad CRUD routes (index GET, crear GET, guardar POST, editar GET, actualizar POST, eliminar POST), gestora routes (gestora/dashboard GET, gestora/crear-aviso GET/POST, gestora/liquidaciones GET), and user profile routes (perfil GET/POST)
2. THE System SHALL implement Laravel Controllers (AuthController, AdminController, IncidenciaController, TecnicoController, EspecialidadController, UserController, GestoraController) where each controller method accepts the same input parameters and produces the same HTTP response (status code, redirect target, session flash messages, and rendered view with identical data variables) as the corresponding method in the existing PHP controllers
3. THE System SHALL implement Eloquent Models (User, Incidencia, Tecnico, Especialidad, Estado, Zona, EmpresaGestora, Comision) with the following relationships: User hasMany Incidencia (as cliente), Incidencia belongsTo User (cliente_id), Incidencia belongsTo Tecnico (tecnico_id), Incidencia belongsTo Estado (estado_id), Incidencia belongsTo Especialidad (especialidad_id), Incidencia belongsTo Zona (zona_id), Incidencia belongsTo EmpresaGestora (gestora_id), Incidencia hasOne Comision, Tecnico hasMany Incidencia, EmpresaGestora hasMany Incidencia, EmpresaGestora hasMany Comision, and Comision belongsTo EmpresaGestora and belongsTo Incidencia
4. THE System SHALL implement Blade templates for all existing views: admin (dashboard, incidencias, tecnicos, especialidades, calendario, crear_incidencia, editar_incidencia, gestoras, crear_gestora, editar_gestora, comisiones_gestora, liquidacion_mensual), cliente (dashboard, mis_avisos, nueva_incidencia), tecnico (agenda), gestora (dashboard, crear_aviso, liquidaciones), auth (login, register), and user (perfil)
5. THE System SHALL use a Blade layout (app.blade.php) as the primary template with @yield sections for content, replacing the existing PHP layout files
6. IF an unauthenticated user requests a protected route, THEN THE System SHALL redirect the user to the login page; IF an authenticated user with role "particular" requests an admin or technician route, THEN THE System SHALL deny access; IF an authenticated user with role "tecnico" requests an admin or client route, THEN THE System SHALL deny access; IF an authenticated user with role "admin" requests any protected route, THEN THE System SHALL allow access
7. WHEN a client creates a standard (estandar) repair request, IF the service date is less than 172800 seconds (48 hours) from the current time, THEN THE Incidencia_Manager SHALL reject the request and display a session flash error message indicating that standard services require 48 hours advance notice
8. WHEN an admin creates a repair request, THE Incidencia_Manager SHALL allow any service date without the 48-hour restriction regardless of urgency type
9. WHEN a client cancels a repair request, IF the service date is less than 172800 seconds (48 hours) from the current time, THEN THE Incidencia_Manager SHALL reject the cancellation and display a session flash error message indicating that cancellation is not permitted within 48 hours of the service date
10. THE System SHALL generate a unique localizador code for each new incidencia following the pattern: letter "R" followed by 6 alphanumeric uppercase characters (A-Z, 0-9), and SHALL ensure no two incidencias share the same localizador value
11. WHEN a new incidencia is created, THE System SHALL assign the initial estado to "Pendiente" by looking up the estado record by name
12. WHEN a user successfully authenticates, IF the user role is "admin", THEN THE System SHALL redirect to /admin/dashboard; IF the user role is "tecnico", THEN THE System SHALL redirect to /tecnico/agenda; IF the user role is "particular", THEN THE System SHALL redirect to /cliente/dashboard
13. WHEN an admin cancels a repair request, THE System SHALL update the incidencia estado to "Cancelada" without applying the 48-hour restriction
14. WHEN a new incidencia is created (by a client, admin, or gestora), THE Incidencia_Manager SHALL automatically assign the precio_base field based on the selected especialidad using the following price map: Fontanería=80.00, Electricidad=65.00, Aire acondicionado=120.00, Bricolaje=45.00, Cerrajería=90.00, Pintura=70.00
15. THE System SHALL include zona_id as a selectable field in the client and gestora incidencia creation forms, loading available zones from the zonas table
16. THE System SHALL render a dynamic navbar in the app.blade.php layout that displays navigation links based on the authenticated user type: for admin role — Dashboard, Incidencias, Calendario, Técnicos, Especialidades, Gestoras, Liquidación Mensual; for tecnico role — Mi Agenda; for particular role — Dashboard, Mis Avisos, Nueva Incidencia; for authenticated gestora — Dashboard, Crear Aviso, Liquidaciones; all navbars SHALL include the user/gestora name, a Perfil link (for users), and a Cerrar Sesión link

### Requirement 3: Gestora Registration and Access [5 pts rúbrica - Panel B2B]

**User Story:** As an admin, I want to register Property Management Companies (Gestoras), so that they can access their own panel and create repair requests on behalf of residents.

#### Acceptance Criteria

1. WHEN the Admin submits the Gestora registration form with nombre (max 150 characters, required), CIF (9-character alphanumeric format: letter-7digits-letter, required), direccion (max 255 characters, optional), telefono (9 to 15 digits, optional), email (valid email format, max 100 characters, required), password (minimum 6 characters, maximum 255 characters, required), and porcentaje_comision (decimal value between 0.00 and 100.00, required), THE Gestora_Module SHALL validate all fields, store the password as a bcrypt hash, create a new Gestora record in the empresas_gestoras table with activa set to true by default, and display a success confirmation message within 3 seconds of submission
2. IF the Admin submits the Gestora registration form with a CIF or email that already exists in the empresas_gestoras table, THEN THE Gestora_Module SHALL reject the submission, display an error message indicating the duplicate field, and preserve the previously entered form data
3. IF the Admin submits the Gestora registration form with any required field (nombre, CIF, email, password, or porcentaje_comision) empty or any field failing its format validation, THEN THE Gestora_Module SHALL reject the submission, display an error message indicating which field is invalid and the reason for rejection, and preserve the previously entered form data
4. WHEN a user submits the login form with an email and password, THE Auth_Module SHALL use a unified login mechanism: first search the usuarios table by email; if not found, search the empresas_gestoras table by email; if found in usuarios, verify the password against the stored bcrypt hash and authenticate as a user (redirecting by role: admin to /admin/dashboard, tecnico to /tecnico/agenda, particular to /cliente/dashboard); if found in empresas_gestoras, verify the password against the stored bcrypt hash and authenticate as a gestora (redirecting to /gestora/dashboard)
5. IF a Gestora record has the activa field set to false, THEN THE Auth_Module SHALL reject the login attempt even if the email and password are correct, and SHALL display an error message indicating that the account is inactive
6. IF a login attempt provides an email that does not exist in either the usuarios or empresas_gestoras table, or a password that does not match the stored hash, THEN THE Auth_Module SHALL display a single generic error message indicating invalid credentials and remain on the login page without revealing which field is incorrect
7. WHILE a Gestora is authenticated, THE System SHALL restrict access to only Gestora-specific routes (gestora/dashboard, gestora/crear-aviso, gestora/liquidaciones) and a logout route that destroys the Gestora session and redirects to the login page
8. IF an authenticated Gestora attempts to access any Admin, Cliente, or Tecnico route, THEN THE System SHALL deny access and redirect the request to the Gestora dashboard panel

### Requirement 4: Gestora Repair Request Creation [5 pts rúbrica - Panel B2B]

**User Story:** As a Gestora, I want to create repair requests on behalf of residents for addresses I manage, so that residents receive repair services through my management.

#### Acceptance Criteria

1. WHEN a Gestora submits a new repair request with especialidad (valid especialidad_id referencing an existing record in the especialidades table), descripcion (between 1 and 1000 characters), direccion (between 1 and 255 characters), fecha_servicio (a valid datetime value in the future), tipo_urgencia (estandar or urgente), nombre_residente (between 1 and 100 characters), and zona_id (valid zona_id referencing an existing record in the zonas table), THE Incidencia_Manager SHALL create a new incidencia record with estado "Pendiente", linked to the Gestora's identifier in the gestora_id field, with cliente_id referencing the admin user (or a generic system user), and with precio_base automatically assigned based on the selected especialidad, then display a success confirmation message
2. IF a Gestora submits a repair request with any required field (especialidad, descripcion, direccion, fecha_servicio, tipo_urgencia, nombre_residente, or zona_id) empty or missing, THEN THE Incidencia_Manager SHALL reject the request, display an error message indicating which fields are required, and preserve the previously entered form data
3. IF a Gestora creates a repair request with tipo_urgencia "estandar" and fecha_servicio is less than 48 hours (172800 seconds) from the current time, THEN THE Incidencia_Manager SHALL reject the request and display an error message indicating that standard requests require at least 48 hours advance notice
4. WHEN a Gestora creates a repair request with tipo_urgencia "urgente", THE Incidencia_Manager SHALL allow any future fecha_servicio without applying the 48-hour minimum advance notice restriction
5. WHEN a Gestora successfully creates a repair request, THE Incidencia_Manager SHALL generate a unique localizador code consisting of the letter "R" followed by 6 uppercase alphanumeric characters (A-Z, 0-9, 7 characters total), ensuring no two incidencias share the same localizador value
6. IF a Gestora submits a repair request with a fecha_servicio that is not in the future or with an especialidad_id that does not reference an existing especialidades record, THEN THE Incidencia_Manager SHALL reject the request and display an error message indicating the invalid field
7. WHEN a Gestora successfully creates a repair request, THE Incidencia_Manager SHALL assign the precio_base field based on the selected especialidad using the price map: Fontanería=80.00, Electricidad=65.00, Aire acondicionado=120.00, Bricolaje=45.00, Cerrajería=90.00, Pintura=70.00

### Requirement 5: Commission Calculation [4 pts rúbrica - Comisiones]

**User Story:** As a platform operator, I want the system to automatically calculate commissions for Gestoras on completed services, so that Gestoras are compensated for the business they bring.

#### Acceptance Criteria

1. WHEN an Admin updates an incidencia's estado to "Finalizada" (completed) via AdminController.updateIncidencia AND the incidencia has a non-null gestora_id, THE Commission_Engine SHALL create a comision record storing the gestora_id, incidencia_id, monto_base (copied from the incidencia's precio_base field at the time of transition), porcentaje_aplicado (the Gestora's porcentaje_comision at the time of calculation), monto_comision, mes, and pagada set to false
2. THE Commission_Engine SHALL calculate the monto_comision by multiplying the incidencia's precio_base by the Gestora's configured porcentaje_comision divided by 100, rounded to two decimal places using half-up rounding
3. THE Commission_Engine SHALL store the first day of the year-month derived from the current date (now()->startOfMonth()) in the mes field of the comision record for monthly grouping
4. IF an incidencia is not linked to any Gestora (gestora_id is NULL), THEN THE Commission_Engine SHALL not create a comision record
5. IF a comision record already exists for a given incidencia_id, THEN THE Commission_Engine SHALL not create a duplicate comision record and SHALL leave the existing record unchanged
6. IF the incidencia's precio_base is NULL or equal to zero, THEN THE Commission_Engine SHALL not create a comision record for that incidencia
7. IF an incidencia that has an existing comision record transitions away from the "Finalizada" state, THEN THE Commission_Engine SHALL delete the associated comision record
8. IF the incidencia's precio_base is a negative value, THEN THE Commission_Engine SHALL not create a comision record for that incidencia

### Requirement 6: Gestora Panel and Liquidation View [5 pts rúbrica - Panel B2B]

**User Story:** As a Gestora, I want to access my professional panel with a dashboard showing my activity summary and view my completed services and accumulated commissions month by month, so that I can track my earnings.

#### Acceptance Criteria

1. WHEN a Gestora accesses the dashboard page, THE Gestora_Module SHALL display a summary panel showing: the count of incidencias created by this Gestora in the current month, the sum of monto_comision from comision records for the current month, and the cumulative total of all monto_comision values from comision records where pagada is false (total pending payout)
2. WHEN a Gestora accesses the liquidaciones page, THE Gestora_Module SHALL display a list of all comision records associated with that Gestora's gestora_id, showing for each record: localizador, descripcion, direccion, fecha_servicio, monto_base, and monto_comision, ordered by fecha_servicio descending
3. WHEN a Gestora accesses the liquidaciones page, THE Gestora_Module SHALL group commission totals by month (formatted as YYYY-MM derived from the mes field), showing the month, number of comision records, and total monto_comision for each month, ordered from most recent month to oldest
4. WHEN a Gestora accesses the liquidaciones page, THE Gestora_Module SHALL display the cumulative total of all monto_comision values from comision records where pagada is false for that Gestora
5. IF the authenticated Gestora has no comision records, THEN THE Gestora_Module SHALL display an empty-state message indicating that no liquidation data is available

### Requirement 7: Admin Commission Management [4 pts rúbrica - Comisiones]

**User Story:** As an admin, I want to view all services processed through Gestoras and the total amounts to be liquidated, so that I can manage monthly payments.

#### Acceptance Criteria

1. WHEN the Admin accesses the Gestora management page, THE System SHALL display a list of all registered Gestoras with their nombre, CIF, porcentaje_comision, activa status, and the total pending commission amount for each Gestora, ordered alphabetically by nombre
2. WHEN the Admin selects a Gestora, THE System SHALL display all incidencias associated with that Gestora showing: localizador, descripcion, fecha_servicio, estado, and monto_comision (if the incidencia has a comision record), ordered by fecha_servicio descending
3. THE System SHALL calculate the total amount pending liquidation for each Gestora as the sum of monto_comision from all comision records that have not been marked as paid (pagada = false)
4. THE System SHALL display a global summary showing the total amount to be paid across all Gestoras for the current calendar month, calculated as the sum of all unpaid comision records (pagada = false) whose mes field matches the year and month of the server date at the time of page load
5. IF a Gestora has no comision records, THEN THE System SHALL display zero (0.00) as the pending liquidation amount for that Gestora
6. WHEN the Admin marks a Gestora's commissions as paid for a given month, THE System SHALL update all comision records for that Gestora where pagada = false and mes matches the selected month by setting pagada = true, and SHALL refresh the displayed totals to reflect the updated state
7. IF no Gestoras are registered in the system, THEN THE System SHALL display an empty-state message indicating that no Gestoras have been registered
8. WHEN the Admin selects a Gestora that has incidencias but none with associated comision records, THE System SHALL display the incidencia list with an empty value in the monto_comision column for each incidencia lacking a comision record
9. WHEN the Admin accesses the Gestora edit form, THE System SHALL allow modification of the Gestora's nombre, CIF, direccion, telefono, email, porcentaje_comision, and activa fields, and SHALL persist the changes upon submission
10. WHEN the Admin sets a Gestora's activa field to false, THE System SHALL prevent that Gestora from logging in until the activa field is set back to true

### Requirement 8: REST API - Services by Zone [4 pts rúbrica]

**User Story:** As an external consumer, I want to query an API endpoint that returns aggregated service data per zone, so that I can analyze service distribution geographically.

#### Acceptance Criteria

1. WHEN a GET request is made to /api/servicios/zonas, THE API_Service SHALL return a JSON response with HTTP status 200
2. WHEN a GET request is made to /api/servicios/zonas, THE API_Service SHALL return a JSON array where each element contains: nombre_zona (string, maximum 100 characters), total_servicios (integer, minimum 0), and porcentaje (decimal between 0.00 and 100.00 representing the percentage relative to total global finalized services)
3. THE API_Service SHALL calculate porcentaje as (total_servicios in zone / total_servicios globally) multiplied by 100, rounded to two decimal places, such that the sum of all porcentaje values is between 99.99 and 100.01 inclusive
4. IF no incidencias with estado "Finalizada" and a non-null zona_id exist in the system, THEN THE API_Service SHALL return an empty JSON array with HTTP status 200
5. THE API_Service SHALL return the response with Content-Type header set to "application/json"
6. THE API_Service SHALL only count incidencias whose estado is "Finalizada" when calculating total_servicios per zone and globally, grouping by zona_id where zona_id is not NULL, with nombre_zona corresponding to the zonas table record
7. WHEN a GET request is made to /api/servicios/zonas, THE API_Service SHALL return the array elements sorted alphabetically by nombre_zona in ascending order
8. IF a request is made to /api/servicios/zonas with an HTTP method other than GET, THEN THE API_Service SHALL return a JSON response with HTTP status 405 and a body containing an error message indicating the method is not allowed
9. THE API_Service SHALL respond to GET /api/servicios/zonas within 2000 milliseconds under normal operating conditions
10. IF a zona exists in the zonas table but has no incidencias with estado "Finalizada" assigned to it, THEN THE API_Service SHALL exclude that zona from the response array

### Requirement 9: Deployment Configuration [requisito obligatorio]

**User Story:** As a developer, I want the application deployed and accessible at the correct URL path on the UOC server, so that it can be accessed in production.

#### Acceptance Criteria

1. WHEN an HTTP request is made to https://fp064.techlab.uoc.edu/~uocx1/producto3, THE System SHALL return an HTTP 200 response with the login page HTML content
2. THE System SHALL be deployed to the server fp064.techlab.uoc.edu at the path ~/public_html/producto3/, with the Laravel project structure accessible under that directory
3. THE System SHALL include a .htaccess file in the project root (~/public_html/producto3/) that rewrites all requests to the public/ subdirectory using mod_rewrite (RewriteRule ^(.*)$ public/$1 [L])
4. THE System SHALL configure RouteServiceProvider to prefix all web routes with /producto3 (via Route::middleware('web')->prefix('producto3')->group(...)) and all API routes with /producto3/api (via Route::middleware('api')->prefix('producto3/api')->group(...)), such that a route like /login is accessible at /~uocx1/producto3/login and /api/servicios/zonas is accessible at /~uocx1/producto3/api/servicios/zonas
5. THE System SHALL resolve all static asset references (CSS, JavaScript, images) using the /producto3 base path, such that each asset request returns an HTTP 200 response with the correct Content-Type header
6. THE System SHALL generate all internal navigation links, form action URLs, and HTTP redirect Location headers (e.g., after login, logout, or form submission) with the /producto3 base path prefix, such that no redirect or link targets a path outside the /producto3 prefix
7. IF a request is made to a route under /producto3 that does not match any defined application route, THEN THE System SHALL return an HTTP 404 response
8. WHEN an HTTP request is made to https://fp064.techlab.uoc.edu/~uocx1/producto3/ (with trailing slash), THE System SHALL treat it equivalently to the path without trailing slash and return the same HTTP 200 response

### Requirement 10: Version Control [3 pts rúbrica]

**User Story:** As a developer, I want proper Git version control with descriptive commits, so that the project history clearly shows the Laravel transition and feature additions.

#### Acceptance Criteria

1. THE System SHALL maintain a Git repository with a minimum of 10 commits where each commit message is written in Spanish using imperative mood (e.g., "Añadir migración de base de datos", "Implementar autenticación") and contains a subject line of no more than 72 characters that begins with a keyword referencing the area of work (migración, gestora, api, comisión, auth, deploy, or config)
2. THE System SHALL use the following feature branches: feature/laravel-init (initial Laravel project setup), feature/auth-middleware (authentication and middleware), feature/crud-migration (database migrations and CRUD operations), feature/b2b-gestoras (B2B Gestora panel and commission system), feature/api-rest (REST API endpoint), and feature/deploy (deployment configuration)
3. THE System SHALL include a .gitignore file that excludes at minimum: vendor/, node_modules/, .env, storage/logs/, .env.backup, and storage/framework/cache/
4. WHEN all commits for a feature branch are complete, THE System SHALL merge it into the main branch using a non-fast-forward merge (--no-ff) that creates a merge commit preserving the individual commit history of the feature branch
5. THE System SHALL ensure that no committed file in the repository contains sensitive credentials such as database passwords, API keys, or secret tokens in plain text
