<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';

class UserController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function perfil(): void
    {
        $this->requireAuth();

        $user = $this->userModel->findById($_SESSION['user']['id']);

        $this->render('user/perfil', [
            'user' => $user
        ]);
    }

    public function updatePerfil(): void
    {
        $this->requireAuth();

        if (!$this->isPost()) {
            $this->redirect('/perfil');
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // VALIDACIONES (CLAVE RÚBRICA)

        if (empty($nombre) || empty($email)) {
            $_SESSION['error'] = 'Nombre y email son obligatorios';
            $this->redirect('/perfil');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email no válido';
            $this->redirect('/perfil');
        }

        if (!empty($password) && strlen($password) < 4) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 4 caracteres';
            $this->redirect('/perfil');
        }

        // PREPARAR DATA (sin hash aquí)
        $data = [
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono
        ];

        if (!empty($password)) {
            $data['password'] = $password; // el model lo hashea
        }

        $updated = $this->userModel->updateProfile(
            $_SESSION['user']['id'],
            $data
        );

        if (!$updated) {
            $_SESSION['error'] = 'Error al actualizar (email duplicado o datos inválidos)';
            $this->redirect('/perfil');
        }

        // ACTUALIZAR SESIÓN CORRECTAMENTE
        $_SESSION['user']['nombre'] = $nombre;
        $_SESSION['user']['email'] = $email;

        $_SESSION['success'] = 'Perfil actualizado correctamente';

        $this->redirect('/perfil');
    }

    public function dashboardCliente(): void
{
    $this->requireAuth();

    $this->render('cliente/dashboard');
}
}