<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use App\Models\Especialidad;
use App\Models\Estado;
use App\Models\Incidencia;
use App\Models\Zona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GestoraController extends Controller
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
     * Show gestora dashboard with summary stats.
     */
    public function dashboard()
    {
        $gestoraId = session('gestora.id');

        // Count incidencias created by this gestora in the current month
        $serviciosMes = Incidencia::where('gestora_id', $gestoraId)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        // Sum comisiones for the current month
        $comisionesMes = Comision::where('gestora_id', $gestoraId)
            ->where('mes', now()->startOfMonth()->toDateString())
            ->sum('monto_comision');

        // Sum all unpaid comisiones (total pending)
        $totalPendiente = Comision::where('gestora_id', $gestoraId)
            ->where('pagada', false)
            ->sum('monto_comision');

        return view('gestora.dashboard', [
            'nombre' => session('gestora.nombre'),
            'serviciosMes' => $serviciosMes,
            'comisionesMes' => $comisionesMes,
            'totalPendiente' => $totalPendiente,
        ]);
    }

    /**
     * Show form to create a new aviso (repair request on behalf of a resident).
     */
    public function crearAviso()
    {
        $especialidades = Especialidad::all();
        $zonas = Zona::all();

        return view('gestora.crear_aviso', [
            'especialidades' => $especialidades,
            'zonas' => $zonas,
        ]);
    }

    /**
     * Store a new aviso created by the gestora.
     */
    public function storeAviso(Request $request)
    {
        $request->validate([
            'nombre_residente' => 'required|max:100',
            'especialidad_id' => 'required|exists:especialidades,id',
            'zona_id' => 'required|exists:zonas,id',
            'direccion' => 'required|max:255',
            'fecha_servicio' => 'required|date',
            'tipo_urgencia' => 'required|in:estandar,urgente',
            'descripcion' => 'required|max:1000',
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
            'cliente_id' => 1, // Admin user as system user for gestora-created incidencias
            'gestora_id' => session('gestora.id'),
            'nombre_residente' => $request->input('nombre_residente'),
            'especialidad_id' => $request->input('especialidad_id'),
            'estado_id' => $estadoPendiente,
            'zona_id' => $request->input('zona_id'),
            'direccion' => $request->input('direccion'),
            'descripcion' => $request->input('descripcion'),
            'fecha_servicio' => $fechaServicio,
            'tipo_urgencia' => $tipoUrgencia,
            'precio_base' => $precioBase,
        ]);

        return redirect('/gestora/dashboard')
            ->with('success', 'Aviso creado correctamente. Localizador: ' . $localizador);
    }

    /**
     * Show liquidaciones (commissions grouped by month).
     */
    public function liquidaciones()
    {
        $gestoraId = session('gestora.id');

        // Group comisiones by month
        $liquidaciones = Comision::where('gestora_id', $gestoraId)
            ->select(
                DB::raw("DATE_FORMAT(mes, '%Y-%m') as periodo"),
                DB::raw('COUNT(*) as num_servicios'),
                DB::raw('SUM(monto_comision) as total_comision')
            )
            ->groupBy('periodo')
            ->orderBy('periodo', 'desc')
            ->get();

        // Individual comision records with incidencia details
        $comisiones = Comision::where('gestora_id', $gestoraId)
            ->with('incidencia')
            ->orderBy('created_at', 'desc')
            ->get();

        // Total pending (unpaid)
        $totalPendiente = Comision::where('gestora_id', $gestoraId)
            ->where('pagada', false)
            ->sum('monto_comision');

        return view('gestora.liquidaciones', [
            'liquidaciones' => $liquidaciones,
            'comisiones' => $comisiones,
            'totalPendiente' => $totalPendiente,
        ]);
    }
}
