<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Incidencia.php';
require_once __DIR__ . '/../models/Tecnico.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Especialidad.php';

class AdminController extends Controller
{
    public function dashboard(): void
    {
        $this->requireAuth();

        if ($_SESSION['user']['rol'] !== 'admin') {
            die('Acceso no autorizado');
        }

        $this->render('admin/dashboard');
    }

    public function incidenciaDetalle(): void
    {
        $this->requireAuth();

        if ($_SESSION['user']['rol'] !== 'admin') {
            die('Acceso no autorizado');
        }

        $incidenciaModel = new Incidencia();
        $tecnicoModel = new Tecnico();

        $incidencias = $incidenciaModel->findAll();
        $tecnicos = $tecnicoModel->getAll();

        $this->render('admin/incidencias', [
            'incidencias' => $incidencias,
            'tecnicos' => $tecnicos
        ]);
    }

    public function asignarTecnico(): void
    {
        $this->requireAuth();

        if ($_SESSION['user']['rol'] !== 'admin') {
            die('Acceso no autorizado');
        }

        $incidenciaId = (int)($_POST['incidencia_id'] ?? 0);
        $tecnicoId = (int)($_POST['tecnico_id'] ?? 0);

        if ($incidenciaId <= 0 || $tecnicoId <= 0) {
            $_SESSION['error'] = 'Datos inválidos';
            $this->redirect('/admin/incidencias');
        }

        $incidenciaModel = new Incidencia();

        $ok = $incidenciaModel->assignTecnico($incidenciaId, $tecnicoId);

        if (!$ok) {
            $_SESSION['error'] = 'No se pudo asignar el técnico';
        } else {
            $_SESSION['success'] = 'Técnico asignado correctamente';
        }

        $this->redirect('/admin/incidencias');
    }

    public function calendario(): void
    {
        $this->requireAuth();

        if ($_SESSION['user']['rol'] !== 'admin') {
            die('Acceso no autorizado');
        }

        $incidenciaModel = new Incidencia();
        $incidencias = $incidenciaModel->findAll();

        $this->render('admin/calendario', [
            'incidencias' => $incidencias
        ]);
    }

    public function crearIncidencia(): void
    {
        $this->requireAuth();

        if ($_SESSION['user']['rol'] !== 'admin') {
            die('Acceso no autorizado');
        }

        $userModel = new User();
        $especialidadModel = new Especialidad();

        $clientes = $userModel->getClientes();
        $especialidades = $especialidadModel->getAll();

        $this->render('admin/crear_incidencia', [
            'clientes' => $clientes,
            'especialidades' => $especialidades
        ]);
    }

    public function storeIncidencia(): void
    {
        $this->requireAuth();

        if (!$this->isPost()) {
            $this->redirect('/admin/crear-incidencia');
        }

        $incidenciaModel = new Incidencia();

        $created = $incidenciaModel->create([
            'cliente_id' => $_POST['cliente_id'],
            'especialidad_id' => $_POST['especialidad_id'],
            'descripcion' => $_POST['descripcion'],
            'direccion' => $_POST['direccion'],
            'fecha_servicio' => $_POST['fecha_servicio'],
            'tipo_urgencia' => $_POST['tipo_urgencia']
        ]);

        if (!$created) {
            $_SESSION['error'] = 'Error al crear incidencia';
            $this->redirect('/admin/crear-incidencia');
        }

        $_SESSION['success'] = 'Incidencia creada correctamente';
        $this->redirect('/admin/dashboard');
    }

    public function editIncidencia(): void
    {
        $this->requireAuth();

        $id = (int)($_GET['id'] ?? 0);

        $incidenciaModel = new Incidencia();
        $especialidadModel = new Especialidad();

        $incidencia = $incidenciaModel->findById($id);
        $especialidades = $especialidadModel->getAll();
        $estados = $incidenciaModel->getEstados();

        $this->render('admin/editar_incidencia', [
            'incidencia' => $incidencia,
            'especialidades' => $especialidades,
            'estados' => $estados
        ]);
    }

    public function updateIncidencia(): void
    {
        $this->requireAuth();

        $id = (int)($_POST['id'] ?? 0);

        $incidenciaModel = new Incidencia();

        $updated = $incidenciaModel->updateAdmin($id, $_POST);

        if (!$updated) {
            $_SESSION['error'] = 'Error al actualizar';
        } else {
            $_SESSION['success'] = 'Incidencia actualizada';
        }

        $this->redirect('/admin/incidencias');
    }

    public function cancelarIncidencia(): void
    {
        $this->requireAuth();

        $id = (int)($_POST['id'] ?? 0);

        $incidenciaModel = new Incidencia();

        $incidenciaModel->cancelAdmin($id);

        $_SESSION['success'] = 'Incidencia cancelada';
        $this->redirect('/admin/incidencias');
    }
}