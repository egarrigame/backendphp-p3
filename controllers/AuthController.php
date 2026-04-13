<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function showLogin(): void
    {
        if (isset($_SESSION['user'])) {
            $this->redirect('/cliente/dashboard');
        }

        $this->render('auth/login', [], 'auth');
    }

    public function login(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // VALIDACIÓN
        if ($email === '' || $password === '') {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            $this->redirect('/login');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email no válido';
            $this->redirect('/login');
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Credenciales incorrectas';
            $this->redirect('/login');
        }

        // SESIÓN
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nombre' => $user['nombre'],
            'email' => $user['email'],
            'rol' => $user['rol']
        ];

        // REDIRECCIÓN POR ROL
        if ($user['rol'] === 'admin') {
            $this->redirect('/admin/dashboard');
        }

        if ($user['rol'] === 'tecnico') {
            $this->redirect('/tecnico/agenda');
        }

        $this->redirect('/cliente/dashboard');
    }

    public function showRegister(): void
    {
        $this->render('auth/register', [], 'auth');
    }

    public function register(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/register');
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // VALIDACIÓN BÁSICA
        if ($nombre === '' || $email === '' || $telefono === '' || $password === '') {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            $this->redirect('/register');
        }

        // EMAIL
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email no válido';
            $this->redirect('/register');
        }

        // PASSWORD
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 4 caracteres';
            $this->redirect('/register');
        }

        // TELÉFONO
        if (!preg_match('/^[0-9]{9,15}$/', $telefono)) {
            $_SESSION['error'] = 'Teléfono no válido';
            $this->redirect('/register');
        }

        // EMAIL DUPLICADO
        if ($this->userModel->findByEmail($email)) {
            $_SESSION['error'] = 'El email ya está registrado';
            $this->redirect('/register');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $created = $this->userModel->create([
            'nombre' => $nombre,
            'email' => $email,
            'password' => $hashedPassword,
            'telefono' => $telefono,
            'rol' => 'particular'
        ]);

        if (!$created) {
            $_SESSION['error'] = 'Error al registrar usuario';
            $this->redirect('/register');
        }

        $_SESSION['success'] = 'Registro completado correctamente';
        $this->redirect('/login');
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        $this->redirect('/login');
    }
}