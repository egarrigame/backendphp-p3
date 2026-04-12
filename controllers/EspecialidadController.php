<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Especialidad.php';

class EspecialidadController extends Controller
{
    private Especialidad $especialidadModel;

    public function __construct()
    {
        $this->especialidadModel = new Especialidad();
    }

    public function index(): void
    {
        $this->requireAuth();

        if (($_SESSION['user']['rol'] ?? '') !== 'admin') {
            die('Acceso no autorizado');
        }

        $especialidades = $this->especialidadModel->getAll();

        $this->render('admin/especialidades', [
            'especialidades' => $especialidades
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();

        if (($_SESSION['user']['rol'] ?? '') !== 'admin') {
            die('Acceso no autorizado');
        }

        $this->redirect('/especialidades');
    }

    public function store(): void
    {
        $this->requireAuth();

        if (($_SESSION['user']['rol'] ?? '') !== 'admin') {
            die('Acceso no autorizado');
        }

        if (!$this->isPost()) {
            $this->redirect('/especialidades');
        }

        $nombre = trim($_POST['nombre_especialidad'] ?? '');

        if ($nombre === '') {
            $_SESSION['error'] = 'El nombre de la especialidad es obligatorio';
            $this->redirect('/especialidades');
        }

        $ok = $this->especialidadModel->create([
            'nombre_especialidad' => $nombre
        ]);

        if (!$ok) {
            $_SESSION['error'] = 'No se pudo crear la especialidad';
            $this->redirect('/especialidades');
        }

        $_SESSION['success'] = 'Especialidad creada correctamente';
        $this->redirect('/especialidades');
    }

    public function edit(): void
    {
        $this->requireAuth();

        if (($_SESSION['user']['rol'] ?? '') !== 'admin') {
            die('Acceso no autorizado');
        }

        $this->redirect('/especialidades');
    }

    public function update(): void
    {
        $this->requireAuth();

        if (($_SESSION['user']['rol'] ?? '') !== 'admin') {
            die('Acceso no autorizado');
        }

        if (!$this->isPost()) {
            $this->redirect('/especialidades');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre_especialidad'] ?? '');

        if ($id <= 0 || $nombre === '') {
            $_SESSION['error'] = 'Datos inválidos';
            $this->redirect('/especialidades');
        }

        $ok = $this->especialidadModel->update($id, [
            'nombre_especialidad' => $nombre
        ]);

        if (!$ok) {
            $_SESSION['error'] = 'No se pudo actualizar la especialidad';
            $this->redirect('/especialidades');
        }

        $_SESSION['success'] = 'Especialidad actualizada correctamente';
        $this->redirect('/especialidades');
    }

    public function delete(): void
    {
        $this->requireAuth();

        if (($_SESSION['user']['rol'] ?? '') !== 'admin') {
            die('Acceso no autorizado');
        }

        if (!$this->isPost()) {
            $this->redirect('/especialidades');
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'ID inválido';
            $this->redirect('/especialidades');
        }

        $ok = $this->especialidadModel->delete($id);

        if (!$ok) {
            $_SESSION['error'] = 'No se pudo eliminar la especialidad';
            $this->redirect('/especialidades');
        }

        $_SESSION['success'] = 'Especialidad eliminada correctamente';
        $this->redirect('/especialidades');
    }
}