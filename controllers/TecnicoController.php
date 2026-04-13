<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Tecnico.php';
require_once __DIR__ . '/../models/Especialidad.php';
require_once __DIR__ . '/../models/Incidencia.php';

class TecnicoController extends Controller
{
    private Tecnico $tecnicoModel;
    private Especialidad $especialidadModel;
    private Incidencia $incidenciaModel;

    public function __construct()
    {
        $this->tecnicoModel = new Tecnico();
        $this->especialidadModel = new Especialidad();
        $this->incidenciaModel = new Incidencia();
    }

    public function index(): void
    {
        $this->requireAuth();

        if (($_SESSION['user']['rol'] ?? '') !== 'admin') {
            die('Acceso no autorizado');
        }

        $tecnicos = $this->tecnicoModel->getAll();
        $especialidades = $this->especialidadModel->getAll();

        $this->render('admin/tecnicos', [
            'tecnicos' => $tecnicos,
            'especialidades' => $especialidades
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();

        if (($_SESSION['user']['rol'] ?? '') !== 'admin') {
            die('Acceso no autorizado');
        }

        if (!$this->isPost()) {
            $this->redirect('/tecnicos');
        }

        $nombre = trim($_POST['nombre_completo'] ?? '');
        $especialidad_id = (int) ($_POST['especialidad_id'] ?? 0);

        if ($nombre === '' || $especialidad_id <= 0) {
            $_SESSION['error'] = 'Datos inválidos';
            $this->redirect('/tecnicos');
        }

        $ok = $this->tecnicoModel->create([
            'nombre_completo' => $nombre,
            'especialidad_id' => $especialidad_id
        ]);

        if (!$ok) {
            $_SESSION['error'] = 'No se pudo crear el técnico';
            $this->redirect('/tecnicos');
        }

        $_SESSION['success'] = 'Técnico creado correctamente';
        $this->redirect('/tecnicos');
    }

    public function update(): void
    {
        $this->requireAuth();

        if (($_SESSION['user']['rol'] ?? '') !== 'admin') {
            die('Acceso no autorizado');
        }

        if (!$this->isPost()) {
            $this->redirect('/tecnicos');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $especialidad_id = (int) ($_POST['especialidad_id'] ?? 0);
        $disponible = (int) ($_POST['disponible'] ?? 1);

        if ($id <= 0) {
            $_SESSION['error'] = 'ID inválido';
            $this->redirect('/tecnicos');
        }

        $ok = $this->tecnicoModel->update($id, [
            'especialidad_id' => $especialidad_id,
            'disponible' => $disponible
        ]);

        if (!$ok) {
            $_SESSION['error'] = 'No se pudo actualizar el técnico';
            $this->redirect('/tecnicos');
        }

        $_SESSION['success'] = 'Técnico actualizado correctamente';
        $this->redirect('/tecnicos');
    }

    public function delete(): void
    {
        $this->requireAuth();

        if (($_SESSION['user']['rol'] ?? '') !== 'admin') {
            die('Acceso no autorizado');
        }

        if (!$this->isPost()) {
            $this->redirect('/tecnicos');
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'ID inválido';
            $this->redirect('/tecnicos');
        }

        $ok = $this->tecnicoModel->delete($id);

        if (!$ok) {
            $_SESSION['error'] = 'No se pudo eliminar el técnico';
            $this->redirect('/tecnicos');
        }

        $_SESSION['success'] = 'Técnico eliminado correctamente';
        $this->redirect('/tecnicos');
    }

    public function agenda(): void
    {
        $this->requireAuth();

        if (($_SESSION['user']['rol'] ?? '') !== 'tecnico') {
            die('Acceso no autorizado');
        }

        // Obtener técnico asociado al usuario logueado
        $tecnico = $this->tecnicoModel->findByUsuarioId($_SESSION['user']['id']);

        if (!$tecnico) {
            die('No se encontró técnico asociado');
        }

        $incidencias = $this->incidenciaModel->findByTecnico($tecnico['id']);

        $this->render('tecnico/agenda', [
            'incidencias' => $incidencias
        ]);
    }
}