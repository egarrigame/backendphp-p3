🛠️ ReparaYa - Backend PHP MVC

Aplicación web desarrollada en PHP siguiendo arquitectura MVC (Modelo - Vista - Controlador).
El proyecto permite gestionar usuarios, incidencias y asignación de técnicos dentro de un sistema de mantenimiento.

📁 Estructura del proyecto

El proyecto sigue una estructura organizada basada en MVC:

PHP-P2/
│
├── app/
│   ├── config/
│   │   └── database.php         # Conexión a base de datos (PDO)
│   │
│   ├── routes/
│   │   └── web.php              # Definición de rutas
│   │
│   ├── controllers/             # Lógica de negocio
│   │   ├── AuthController.php
│   │   ├── UserController.php
│   │   ├── IncidenciaController.php
│   │   ├── AdminController.php
│   │   ├── TecnicoController.php
│   │   └── EspecialidadController.php
│   │
│   ├── models/                  # Acceso a base de datos
│   │   ├── User.php
│   │   ├── Incidencia.php
│   │   ├── Tecnico.php
│   │   └── Especialidad.php
│   │
│   ├── views/                   # Vistas (interfaz)
│   │   ├── auth/
│   │   │   ├── login.php
│   │   │   └── register.php
│   │   │
│   │   ├── admin/
│   │   ├── cliente/
│   │   ├── tecnico/
│   │   ├── user/
│   │   │   └── perfil.php
│   │   │
│   │   └── layouts/             # Layout base (navbar, footer...)
│   │       ├── app.php
│   │       └── auth.php
│   │
│   └── core/                   # Núcleo del sistema
│       ├── Router.php
│       ├── Controller.php
│       └── Model.php
│
├── public/
│   └── index.php               # Punto de entrada (Front Controller)
│
└── database.sql                # Script de base de datos

🧠 Arquitectura MVC
Modelo (Model):
Gestiona la base de datos mediante PDO. Contiene lógica de acceso a datos.
Vista (View):
Archivos PHP que renderizan la interfaz (formularios, tablas, etc).
Controlador (Controller):
Recibe las peticiones, valida datos, ejecuta lógica y conecta Modelo con Vista.
Router:
Gestiona las rutas y decide qué controlador ejecutar.
Front Controller (public/index.php):
Punto de entrada único de la aplicación.