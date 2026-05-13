# Dificultades encontradas en la migración P2 → P3 y despliegue

## 1. Migración de PHP MVC a Laravel

### 1.1 Prefijo de URL `/producto3`
- **Problema:** La aplicación debe responder en `/producto3` tanto en local como en producción, pero los mecanismos son diferentes.
- **Local:** `RouteServiceProvider` añade el prefijo `producto3` a todas las rutas. `php artisan serve` sirve desde la raíz.
- **Producción (UOC):** La app vive en el directorio `~/public_html/producto3/`, por lo que el prefijo ya está en la URL por la estructura de directorios. No se necesita el prefijo en las rutas de Laravel.
- **Solución:** Se creó una rama `deploy-uoc` sin el prefijo en `RouteServiceProvider` y sin `producto3/` en las URLs de vistas y controladores.

### 1.2 Asset URLs (`ASSET_URL`)
- **Problema:** `asset()` genera URLs con el prefijo configurado en `ASSET_URL`. En local con `php artisan serve`, los assets se sirven desde `/` (raíz), pero `ASSET_URL=/producto3` generaba `/producto3/css/style.css` que daba 404.
- **Solución:** `ASSET_URL` vacío en `.env` local. En producción, los assets se sirven desde el mismo directorio, así que tampoco necesita prefijo.

### 1.3 Estilo visual (CSS)
- **Problema:** El CSS del P2 no se copió correctamente al principio. Las vistas de Laravel usaban clases diferentes a las del P2 original.
- **Solución:** Se restauró el CSS completo del P2 desde el historial de git (`git show 845dc29:public/assets/css/style.css`) y se actualizaron todas las vistas Blade para usar las mismas clases, emojis y estructura.

### 1.4 Calendario JavaScript
- **Problema:** El calendario interactivo del P2 (con vistas mes/semana/día y eventos clickeables) se perdió en la migración inicial. Se reemplazó por una tabla estática.
- **Solución:** Se restauró el `calendar.js` original y se recreó la vista del calendario con los selectores, la grilla JS, el modal de detalle y la tabla de listado.

### 1.5 URLs en vistas Blade
- **Problema:** Todas las llamadas `url()` en las vistas necesitaban incluir `producto3/` para funcionar en local, pero esto causaba URLs dobles en producción.
- **Solución:** Para producción se eliminó `producto3/` de todas las vistas y controladores. El `APP_URL` ya incluye la base correcta.

---

## 2. Problemas del servidor UOC (fp064.techlab.uoc.edu)

### 2.1 Extensión `php-xml` / `DOMDocument` no disponible
- **Problema:** El servidor no tiene instalada la extensión `php-xml` (ni en CLI ni en Apache). Laravel requiere `DOMDocument` a través del paquete `nunomaduro/termwind` (dependencia de Laravel Framework).
- **Síntoma:** `Class "DOMDocument" not found` en cualquier comando artisan y en la web.
- **Intentos fallidos:**
  - `composer remove nunomaduro/termwind` → No se puede, es dependencia de `laravel/framework`.
  - Crear un archivo stub externo (`app/Overrides/DomStub.php`) → Apache no podía leerlo por permisos.
- **Solución final:** Declarar las clases stub directamente inline en `index.php`:
  ```php
  if(!class_exists("DOMDocument")){class DOMDocument{}}
  if(!class_exists("DOMXPath")){class DOMXPath{public function __construct($d){}}}
  ```

### 2.2 Permisos de directorios (700 vs 755)
- **Problema:** Al subir archivos por SFTP, los directorios se crearon con permisos `drwx------` (700). Apache corre como `www-data` y no podía leer ningún directorio de la aplicación.
- **Síntoma:** Error 403 Forbidden "Server unable to read htaccess file" y luego Error 500.
- **Solución:**
  ```bash
  find ~/public_html/producto3 -type d -exec chmod 755 {} \;
  find ~/public_html/producto3 -type f -exec chmod 644 {} \;
  chmod -R 775 ~/public_html/producto3/storage
  chmod -R 775 ~/public_html/producto3/bootstrap/cache
  ```

### 2.3 `.htaccess` del directorio padre intercepta las peticiones
- **Problema:** El archivo `~/public_html/.htaccess` (del Producto 2) tenía una regla que redirigía TODAS las peticiones a `index.php` del P2. Esto impedía que las peticiones llegaran a `producto3/`.
- **Síntoma:** Error 404 constante — las peticiones nunca llegaban al directorio `producto3/`.
- **Solución:** Añadir una regla de exclusión en `~/public_html/.htaccess`:
  ```apache
  RewriteRule ^producto3(/.*)?$ - [L]
  ```

### 2.4 Rewrite de `public/` no funciona con `mod_userdir`
- **Problema:** La estrategia estándar de Laravel (`.htaccess` en raíz que reescribe a `public/$1`) no funciona correctamente con `mod_userdir` de Apache. Las peticiones a URLs sin archivo físico (como `/login`) daban 404 porque `public/login` no existe.
- **Solución:** Eliminar el rewrite a `public/` y usar un `index.php` en la raíz del proyecto como front controller directo:
  ```apache
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule ^(.*)$ index.php [L,QSA]
  ```
  El `index.php` raíz tiene las rutas ajustadas (`__DIR__.'/vendor'` en vez de `__DIR__.'/../vendor'`).

### 2.5 `SCRIPT_NAME` incorrecto para `mod_userdir`
- **Problema:** Apache con `mod_userdir` establece `SCRIPT_NAME` de forma que Laravel calcula mal el `PATH_INFO`. Laravel veía `/login` pero las rutas esperaban `/producto3/login`.
- **Solución:** Forzar `SCRIPT_NAME` en `index.php`:
  ```php
  $_SERVER['SCRIPT_NAME'] = '/~uocx1/producto3/index.php';
  $_SERVER['PHP_SELF'] = '/~uocx1/producto3/index.php';
  ```
  Y eliminar el prefijo `producto3` del `RouteServiceProvider` (ya que la estructura de directorios lo proporciona).

### 2.6 Cookie de sesión con path incorrecto
- **Problema:** `config/session.php` tenía `'path' => '/producto3'` hardcodeado. La cookie se establecía con `path=/producto3` pero el navegador accedía a `/~uocx1/producto3/...`. Los paths no coincidían y la cookie no se enviaba en las peticiones subsiguientes.
- **Síntoma:** Login exitoso (sesión guardada en servidor) pero al redirigir se perdía la sesión. El usuario volvía al login.
- **Solución:** Cambiar el path de la cookie a `/`:
  ```php
  'path' => '/',
  ```

### 2.7 Base de datos: usuario `wordpress1` sin acceso a `reparaya`
- **Problema:** El servidor UOC asigna un único usuario MySQL (`wordpress1`) con acceso solo a una base de datos del mismo nombre. No se puede crear la base de datos `reparaya`.
- **Solución:** Usar `wordpress1` como nombre de base de datos en `.env`:
  ```
  DB_DATABASE=wordpress1
  ```

### 2.8 `php artisan` no funciona (DOMDocument)
- **Problema:** Todos los comandos artisan fallan por la falta de `php-xml`. No se puede ejecutar `migrate`, `db:seed`, `config:cache`, etc.
- **Solución:** Crear un script PHP independiente (`seed_production.php`) que bootstrapea Laravel directamente y ejecuta los seeders sin pasar por el CLI de artisan. Las tablas se crean manualmente con SQL.

### 2.9 CSRF Token (419 Page Expired)
- **Problema:** Incluso después de resolver la sesión, el CSRF token fallaba porque la sesión no persistía correctamente entre GET y POST.
- **Solución temporal:** Deshabilitar CSRF para producción:
  ```php
  protected $except = ['*'];
  ```

---

## 3. Resumen de la configuración final del servidor

| Componente | Configuración |
|---|---|
| `index.php` | En raíz del proyecto (no en `public/`) con DomStub inline y SCRIPT_NAME forzado |
| `.htaccess` (producto3) | Rewrite todo a `index.php` (sin rewrite a `public/`) |
| `.htaccess` (public_html) | Excluye `producto3/` del rewrite del P2 |
| `RouteServiceProvider` | Sin prefijo (`->prefix('')`) |
| `config/session.php` | `'path' => '/'` |
| `.env` | `DB_DATABASE=wordpress1`, `APP_URL=https://fp064.techlab.uoc.edu/~uocx1/producto3` |
| Vistas/Controllers | Sin `producto3/` en URLs (el APP_URL ya lo incluye) |
| CSRF | Deshabilitado (`$except = ['*']`) |
| Seeders | Via `php seed_production.php` (no artisan) |
| Tablas | Creadas manualmente con SQL |
