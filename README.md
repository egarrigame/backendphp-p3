# ReparaYa - Producto 3

Sistema de gestión de incidencias de reparación migrado a **Laravel 10** con módulo B2B para Empresas Gestoras (Administradores de Fincas), cálculo automático de comisiones y API REST.

## Estructura de Ramas

| Rama | Descripción |
|---|---|
| `main` | Producto 3 completo para desarrollo local. Usa prefijo `/producto3` en rutas y funciona con `php artisan serve` |
| `leonardo` | Rama de desarrollo (sincronizada con main) |
| `deploy-uoc` | **Versión adaptada al servidor UOC**. Sin prefijo en rutas, con DomStub para php-xml, session path `/`, CSRF deshabilitado |
| `feature/*` | Ramas de funcionalidades individuales (historial de desarrollo) |

### Diferencias entre `main` y `deploy-uoc`

La rama `deploy-uoc` contiene adaptaciones específicas para el servidor UOC (`fp064.techlab.uoc.edu`) que no tiene `php-xml` instalado y usa `mod_userdir`:

| Aspecto | `main` (local) | `deploy-uoc` (servidor) |
|---|---|---|
| Prefijo de rutas | `producto3` en RouteServiceProvider | Sin prefijo (el directorio ya lo proporciona) |
| URLs en vistas/controllers | `url('producto3/admin/...')` | `url('admin/...')` |
| Entry point | `public/index.php` | `index.php` en raíz con paths ajustados |
| DOMDocument | Disponible (php-xml instalado) | Stub inline en `index.php` |
| SCRIPT_NAME | Automático | Forzado a `/~uocx1/producto3/index.php` |
| Session cookie path | `/producto3` | `/` |
| CSRF | Habilitado | Deshabilitado (problemas de sesión en shared hosting) |
| `.htaccess` | Rewrite a `public/$1` | Rewrite directo a `index.php` |
| Seeders | `php artisan db:seed` | `php seed_production.php` (artisan no funciona sin php-xml) |

### Para desarrollo local

Usar la rama `main`:
```bash
git checkout main
php artisan serve
# Acceder a http://localhost:8000/producto3/login
```

### Para desplegar en el servidor UOC

Usar la rama `deploy-uoc`:
```bash
git checkout deploy-uoc
# Subir por SFTP a ~/public_html/producto3/
# Ver docs/dificultades_despliegue.md para instrucciones detalladas
```

## Descripción

ReparaYa es una plataforma que conecta clientes con técnicos de reparación. La aplicación permite:

- **Clientes**: Crear y gestionar avisos de reparación
- **Administradores**: Gestionar incidencias, técnicos, especialidades y empresas gestoras
- **Técnicos**: Consultar su agenda de servicios asignados
- **Empresas Gestoras (B2B)**: Crear avisos en nombre de residentes y consultar liquidaciones de comisiones
- **API REST**: Endpoint público con datos agregados de servicios por zona

## Requisitos del Sistema

| Componente | Versión |
|---|---|
| PHP | >= 8.1 (recomendado 8.2) |
| MySQL | 8.0 |
| Composer | >= 2.0 |
| Apache | Con mod_rewrite habilitado |

### Extensiones PHP requeridas

- BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, PDO_MySQL, Tokenizer, XML

## Instalación Local (Desarrollo)

### 1. Clonar el repositorio

```bash
git clone <url-repositorio> backendphp-p3
cd backendphp-p3
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

Editar `.env` con los datos de la base de datos local:

```env
APP_URL=http://localhost:8000/producto3
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reparaya
DB_USERNAME=root
DB_PASSWORD=1234
```

### 4. Crear la base de datos y ejecutar migraciones

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS reparaya CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate
php artisan db:seed
```

### 5. Iniciar el servidor de desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000/producto3/login`

## Despliegue en Producción (Servidor UOC)

### Datos del servidor

| Parámetro | Valor |
|---|---|
| Servidor | fp064.techlab.uoc.edu |
| Puerto SSH/SFTP | 55000 |
| Usuario | uocx1 |
| Ruta de despliegue | ~/public_html/producto3/ |
| Base de datos | reparaya |
| Usuario BD | wordpress1 |
| URL aplicación | https://fp064.techlab.uoc.edu/~uocx1/producto3 |

### Pasos de despliegue

#### Paso 1: Preparar archivos localmente

```bash
# Instalar dependencias de producción
composer install --no-dev --optimize-autoloader
```

#### Paso 2: Subir archivos al servidor

Conectar por SFTP al puerto 55000 y subir todo el proyecto a `~/public_html/producto3/`:

```bash
sftp -P 55000 uocx1@fp064.techlab.uoc.edu
sftp> put -r . public_html/producto3/
```

> **Nota:** Si Composer no está disponible en el servidor, ejecutar `composer install --no-dev` localmente y subir la carpeta `vendor/` junto con el resto del proyecto.

#### Paso 3: Configurar el entorno en el servidor

Conectar por SSH:

```bash
ssh -p 55000 uocx1@fp064.techlab.uoc.edu
cd ~/public_html/producto3
```

Crear/editar el archivo `.env` de producción:

```env
APP_NAME=ReparaYa
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://fp064.techlab.uoc.edu/~uocx1/producto3

LOG_CHANNEL=single

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=reparaya
DB_USERNAME=wordpress1
DB_PASSWORD=DWD8Ds3l4dvXpjZH

SESSION_DRIVER=file
SESSION_LIFETIME=120
```

#### Paso 4: Generar clave de aplicación

```bash
php artisan key:generate
```

#### Paso 5: Ejecutar migraciones y seeders

```bash
php artisan migrate --force
php artisan db:seed --force
```

#### Paso 6: Optimizar para producción

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Paso 7: Configurar permisos

```bash
chmod -R 775 storage bootstrap/cache
```

#### Paso 8: Verificar el despliegue

- **Aplicación web:** https://fp064.techlab.uoc.edu/~uocx1/producto3/login
- **API REST:** https://fp064.techlab.uoc.edu/~uocx1/producto3/api/servicios/zonas

### Solución de problemas en producción

Si hay errores tras el despliegue:

```bash
# Limpiar cachés
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Verificar logs
tail -f storage/logs/laravel.log

# Regenerar autoload
composer dump-autoload --optimize
```

## Credenciales de Prueba

| Rol | Email | Contraseña | Notas |
|---|---|---|---|
| Admin | root@uoc.edu | 123456 | Acceso completo |
| Técnico | tecnico@uoc.com | 123456 | Agenda de servicios |
| Cliente | cliente@uoc.com | 123456 | Panel de avisos |
| Gestora 1 | fincas@lopez.com | 123456 | Comisión 10% |
| Gestora 2 | gestiones@martinez.com | 123456 | Comisión 5% |

## Endpoints de la API

### GET /producto3/api/servicios/zonas

Devuelve datos agregados de servicios finalizados por zona geográfica.

**URL completa:** `https://fp064.techlab.uoc.edu/~uocx1/producto3/api/servicios/zonas`

**Respuesta exitosa (200):**

```json
[
  {
    "nombre_zona": "Centro",
    "total_servicios": 5,
    "porcentaje": 33.33
  },
  {
    "nombre_zona": "Norte",
    "total_servicios": 3,
    "porcentaje": 20.00
  }
]
```

**Campos de respuesta:**

| Campo | Tipo | Descripción |
|---|---|---|
| nombre_zona | string | Nombre de la zona geográfica |
| total_servicios | integer | Número de servicios finalizados en la zona |
| porcentaje | decimal | Porcentaje respecto al total global (suma ≈ 100%) |

**Notas:**
- Solo cuenta incidencias con estado "Finalizada" y zona asignada
- Zonas sin servicios finalizados no aparecen en la respuesta
- Si no hay servicios finalizados, devuelve un array vacío `[]`
- Resultados ordenados alfabéticamente por nombre_zona

## Estructura del Proyecto

```
producto3/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Controladores (Auth, Admin, Cliente, Tecnico, Gestora, Api)
│   │   └── Middleware/      # AuthCheck, RoleMiddleware, GestoraMiddleware
│   ├── Models/              # Modelos Eloquent
│   └── Providers/           # RouteServiceProvider (prefijo /producto3)
├── database/
│   ├── migrations/          # Migraciones de BD
│   └── seeders/             # Datos iniciales
├── resources/views/         # Plantillas Blade
├── routes/
│   ├── web.php              # Rutas web (prefijo /producto3)
│   └── api.php              # Rutas API (prefijo /producto3/api)
├── public/                  # Entry point (index.php, assets)
├── .htaccess                # Rewrite a public/ para servidor UOC
├── .env.production          # Template de configuración producción
└── composer.json            # Dependencias PHP
```

## Tecnologías

- **Backend:** Laravel 10 (PHP 8.2)
- **Base de datos:** MySQL 8.0
- **Frontend:** Blade Templates + Bootstrap 5.3 CDN
- **Tipografía:** Google Fonts (Inter)
- **Servidor:** Apache con mod_rewrite
