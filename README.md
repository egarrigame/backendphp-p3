# 🛠️ ReparaYa - Backend PHP MVC

Aplicación web desarrollada en PHP siguiendo arquitectura MVC (Modelo - Vista - Controlador).  
El proyecto permite gestionar usuarios, incidencias y asignación de técnicos dentro de un sistema de mantenimiento.

---

## 📁 Estructura del proyecto

El proyecto sigue una estructura organizada basada en MVC:


PHP-P2/
│
├── app/
│ ├── config/
│ │ └── database.php
│ │
│ ├── routes/
│ │ └── web.php
│ │
│ ├── controllers/
│ │ ├── AuthController.php
│ │ ├── UserController.php
│ │ ├── IncidenciaController.php
│ │ ├── AdminController.php
│ │ ├── TecnicoController.php
│ │ └── EspecialidadController.php
│ │
│ ├── models/
│ │ ├── User.php
│ │ ├── Incidencia.php
│ │ ├── Tecnico.php
│ │ └── Especialidad.php
│ │
│ ├── views/
│ │ ├── auth/
│ │ │ ├── login.php
│ │ │ └── register.php
│ │ │
│ │ ├── admin/
│ │ │ ├── dashboard.php
│ │ │ ├── incidencias.php
│ │ │ ├── crear_incidencia.php
│ │ │ ├── editar_incidencia.php
│ │ │ ├── tecnicos.php
│ │ │ ├── especialidades.php
│ │ │ └── calendario.php
│ │ │
│ │ ├── cliente/
│ │ │ ├── dashboard.php
│ │ │ ├── mis_avisos.php
│ │ │ └── nueva_incidencia.php
│ │ │
│ │ ├── tecnico/
│ │ │ └── agenda.php
│ │ │
│ │ ├── user/
│ │ │ └── perfil.php
│ │ │
│ │ └── layouts/
│ │ ├── app.php
│ │ └── auth.php
│ │
│ └── core/
│ ├── Router.php
│ ├── Controller.php
│ └── Model.php
│
├── public/
│ └── index.php
│
└── database.sql


---

## 🧠 Arquitectura MVC

- **Modelo (Model)**  
  Gestiona la base de datos mediante PDO. Contiene la lógica de acceso a datos.

- **Vista (View)**  
  Archivos PHP que renderizan la interfaz (formularios, tablas, etc).

- **Controlador (Controller)**  
  Recibe las peticiones, valida datos, ejecuta lógica y conecta Modelo con Vista.

- **Router**  
  Gestiona las rutas y decide qué controlador ejecutar.

- **Front Controller (`public/index.php`)**  
  Punto de entrada único de la aplicación.

---

## ⚙️ Requisitos

- PHP >= 8.0
- MySQL / MariaDB
- Apache o servidor compatible

## 🔐 Seguridad
- Uso de sentencias preparadas (PDO)
- Protección contra SQL Injection
- Contraseñas cifradas con password_hash()
- Verificación con password_verify()
- Gestión de sesiones

## 👤 Roles de usuario
- Admin → gestión completa
root@uoc.edu / password: 123456

Técnico → acceso a agenda
tecnico@uoc.com / password: 123456

Cliente (particular) → creación y consulta de incidencias
cliente@uoc.com / password: 123456

## 🚀 Funcionalidades
- Registro y login de usuarios
- Gestión de perfiles
- Creación de incidencias
- Asignación de técnicos
- Panel de administración
- Agenda de técnicos
- Gestión de especialidades