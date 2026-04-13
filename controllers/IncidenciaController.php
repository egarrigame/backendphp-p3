<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Incidencia.php';
require_once __DIR__ . '/../models/Especialidad.php';

class IncidenciaController extends Controller
{
    public function create(): void
    {
        $this->requireAuth();

        $especialidadModel = new Especialidad();
        $especialidades = $especialidadModel->getAll();

        $this->render('cliente/nueva-incidencia', [
            'especialidades' => $especialidades
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();

        if (!$this->isPost()) {
            $this->redirect('/cliente/nueva-incidencia');
        }

        $fecha = $_POST['fecha_servicio'] ?? '';
        $tipoRaw = $_POST['tipo_urgencia'] ?? '';

        switch (strtolower(trim($tipoRaw))) {
            case 'urgente':
                $tipo = 'urgente';
                break;

            case 'estandar':
            case 'estándar':
                $tipo = 'estandar';
                break;

            default:
                $_SESSION['error'] = 'Tipo de urgencia inválido';
                $this->redirect('/cliente/nueva-incidencia');
        }

        if (
            empty($fecha) ||
            empty($_POST['especialidad_id']) ||
            empty($_POST['descripcion']) ||
            empty($_POST['direccion'])
        ) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            $this->redirect('/cliente/nueva-incidencia');
        }

        // REGLA 48H SOLO USER
        if ($tipo === 'estandar') {
            $fechaServicio = new DateTime($fecha);
            $ahora = new DateTime();

            $diffSegundos = $fechaServicio->getTimestamp() - $ahora->getTimestamp();

            if ($diffSegundos < 172800) {
                $_SESSION['error'] = 'Los servicios estándar requieren 48h de antelación';
                $this->redirect('/cliente/nueva-incidencia');
            }
        }

        $incidenciaModel = new Incidencia();

        $created = $incidenciaModel->create([
            'cliente_id' => $_SESSION['user']['id'],
            'especialidad_id' => $_POST['especialidad_id'],
            'descripcion' => $_POST['descripcion'],
            'direccion' => $_POST['direccion'],
            'fecha_servicio' => $fecha,
            'tipo_urgencia' => $tipo,
            'is_admin' => false 
        ]);

        if (!$created) {
            $_SESSION['error'] = 'Error al crear incidencia';
            $this->redirect('/cliente/nueva-incidencia');
        }

        $_SESSION['success'] = 'Incidencia creada correctamente';
        $this->redirect('/cliente/dashboard');
    }

    public function misAvisos(): void
    {
        $this->requireAuth();

        $incidenciaModel = new Incidencia();

        $avisos = $incidenciaModel->findByCliente($_SESSION['user']['id']);

        $this->render('cliente/mis_avisos', [
            'avisos' => $avisos
        ]);
    }

    public function cancel(): void
    {
        $this->requireAuth();

        if (!$this->isPost()) {
            $this->redirect('/cliente/dashboard');
        }

        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'Incidencia no válida';
            $this->redirect('/cliente/dashboard');
        }

        $incidenciaModel = new Incidencia();
        $incidencia = $incidenciaModel->findById($id);

        if (!$incidencia) {
            $_SESSION['error'] = 'Incidencia no encontrada';
            $this->redirect('/cliente/dashboard');
        }

        if ($incidencia['cliente_id'] != $_SESSION['user']['id']) {
            $_SESSION['error'] = 'No autorizado';
            $this->redirect('/cliente/dashboard');
        }

        // REGLA 48H CANCELACIÓN
        $fechaServicio = new DateTime($incidencia['fecha_servicio']);
        $ahora = new DateTime();

        $diffSegundos = $fechaServicio->getTimestamp() - $ahora->getTimestamp();

        if ($diffSegundos < 172800) {
            $_SESSION['error'] = 'No puedes cancelar con menos de 48h de antelación';
            $this->redirect('/cliente/dashboard');
        }

        $cancelled = $incidenciaModel->cancel($id, $_SESSION['user']['id']);

        if (!$cancelled) {
            $_SESSION['error'] = 'No se pudo cancelar la incidencia';
            $this->redirect('/cliente/dashboard');
        }

        $_SESSION['success'] = 'Incidencia cancelada correctamente';
        $this->redirect('/cliente/dashboard');
    }
}