<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use App\Models\Estado;
use App\Models\Incidencia;
use App\Models\Zona;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Price map: especialidad name => base price.
     */
    private const PRECIOS = [
        'Fontanería' => 80.00,
        'Electricidad' => 65.00,
        'Aire acondicionado' => 120.00,
        'Bricolaje' => 45.00,
        'Cerrajería' => 90.00,
        'Pintura' => 70.00,
    ];

    /**
     * Show client dashboard.
     */
    public function dashboard()
    {
        return view('cliente.dashboard', [
            'nombre' => session('user.nombre'),
        ]);
    }

    /**
     * List all incidencias for the authenticated client.
     */
    public function misAvisos()
    {
        $incidencias = Incidencia::where('cliente_id', session('user.id'))
            ->with(['estado', 'especialidad'])
            ->orderBy('fecha_servicio', 'desc')
            ->get();

        return view('cliente.mis_avisos', [
            'incidencias' => $incidencias,
        ]);
    }

    /**
     * Show form to create a new incidencia.
     */
    public function create()
    {
        $especialidades = Especialidad::all();
        $zonas = Zona::all();

        return view('cliente.nueva_incidencia', [
            'especialidades' => $especialidades,
            'zonas' => $zonas,
        ]);
    }

    /**
     * Store a new incidencia.
     */
    public function store(Request $request)
    {
        $request->validate([
            'especialidad_id' => 'required|exists:especialidades,id',
            'zona_id' => 'required|exists:zonas,id',
            'direccion' => 'required',
            'descripcion' => 'required',
            'fecha_servicio' => 'required|date',
            'tipo_urgencia' => 'required|in:estandar,urgente',
        ]);

        $tipoUrgencia = strtolower(trim($request->input('tipo_urgencia')));
        $fechaServicio = $request->input('fecha_servicio');

        // Apply 48h rule for estandar urgency
        if ($tipoUrgencia === 'estandar' && (strtotime($fechaServicio) - time()) < 172800) {
            return redirect()->back()
                ->with('error', 'Para servicios estándar, la fecha debe ser al menos 48 horas en el futuro.')
                ->withInput();
        }

        // Get precio_base from especialidad
        $especialidad = Especialidad::findOrFail($request->input('especialidad_id'));
        $precioBase = self::PRECIOS[$especialidad->nombre_especialidad] ?? 0;

        // Get estado Pendiente
        $estadoPendiente = Estado::where('nombre_estado', 'Pendiente')->first()->id;

        // Generate localizador
        $localizador = Incidencia::generarLocalizador();

        Incidencia::create([
            'localizador' => $localizador,
            'cliente_id' => session('user.id'),
            'especialidad_id' => $request->input('especialidad_id'),
            'estado_id' => $estadoPendiente,
            'zona_id' => $request->input('zona_id'),
            'direccion' => $request->input('direccion'),
            'descripcion' => $request->input('descripcion'),
            'fecha_servicio' => $fechaServicio,
            'tipo_urgencia' => $tipoUrgencia,
            'precio_base' => $precioBase,
        ]);

        return redirect('/cliente/mis-avisos')
            ->with('success', 'Incidencia creada correctamente. Localizador: ' . $localizador);
    }

    /**
     * Cancel an incidencia.
     */
    public function cancel(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:incidencias,id',
        ]);

        $incidencia = Incidencia::where('id', $request->input('id'))
            ->where('cliente_id', session('user.id'))
            ->firstOrFail();

        // Apply 48h rule: cannot cancel if service date is less than 48h away
        if ((strtotime($incidencia->fecha_servicio) - time()) < 172800) {
            return redirect()->back()
                ->with('error', 'No se puede cancelar una incidencia con menos de 48 horas de antelación.');
        }

        // Change estado to Cancelada
        $estadoCancelada = Estado::where('nombre_estado', 'Cancelada')->first()->id;
        $incidencia->update(['estado_id' => $estadoCancelada]);

        return redirect('/cliente/mis-avisos')
            ->with('success', 'Incidencia cancelada correctamente.');
    }
}
