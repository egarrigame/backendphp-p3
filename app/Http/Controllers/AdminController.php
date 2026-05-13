<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use App\Models\EmpresaGestora;
use App\Models\Especialidad;
use App\Models\Estado;
use App\Models\Incidencia;
use App\Models\Tecnico;
use App\Models\User;
use App\Models\Zona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Price map: especialidad name => precio_base.
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
     * Admin dashboard with quick-access cards.
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * List all incidencias with relations + all tecnicos for assignment.
     */
    public function incidencias()
    {
        $incidencias = Incidencia::with(['cliente', 'tecnico', 'estado', 'especialidad'])
            ->orderBy('created_at', 'desc')
            ->get();

        $tecnicos = Tecnico::all();

        return view('admin.incidencias', compact('incidencias', 'tecnicos'));
    }

    /**
     * Show form to create a new incidencia (admin).
     */
    public function crearIncidencia()
    {
        $clientes = User::where('rol', 'particular')->get();
        $especialidades = Especialidad::all();
        $zonas = Zona::all();

        return view('admin.crear_incidencia', compact('clientes', 'especialidades', 'zonas'));
    }

    /**
     * Store a new incidencia (admin - NO 48h restriction).
     */
    public function storeIncidencia(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:usuarios,id',
            'especialidad_id' => 'required|exists:especialidades,id',
            'zona_id' => 'required|exists:zonas,id',
            'descripcion' => 'required',
            'direccion' => 'required',
            'fecha_servicio' => 'required|date',
            'tipo_urgencia' => 'required|in:estandar,urgente',
        ]);

        $especialidad = Especialidad::findOrFail($request->input('especialidad_id'));
        $precioBase = self::PRECIOS[$especialidad->nombre_especialidad] ?? 0;

        $estadoPendiente = Estado::where('nombre_estado', 'Pendiente')->first();

        Incidencia::create([
            'localizador' => Incidencia::generarLocalizador(),
            'cliente_id' => $request->input('cliente_id'),
            'especialidad_id' => $request->input('especialidad_id'),
            'estado_id' => $estadoPendiente->id,
            'zona_id' => $request->input('zona_id'),
            'descripcion' => $request->input('descripcion'),
            'direccion' => $request->input('direccion'),
            'fecha_servicio' => $request->input('fecha_servicio'),
            'tipo_urgencia' => $request->input('tipo_urgencia'),
            'precio_base' => $precioBase,
        ]);

        return redirect('/admin/incidencias')->with('success', 'Incidencia creada correctamente.');
    }

    /**
     * Show edit form for an incidencia.
     */
    public function editIncidencia($id)
    {
        $incidencia = Incidencia::findOrFail($id);
        $especialidades = Especialidad::all();
        $estados = Estado::all();

        return view('admin.editar_incidencia', compact('incidencia', 'especialidades', 'estados'));
    }

    /**
     * Update an incidencia. CRITICAL: commission logic on state transitions.
     */
    public function updateIncidencia(Request $request, $id)
    {
        $incidencia = Incidencia::findOrFail($id);
        $estadoAnterior = $incidencia->estado_id;

        $request->validate([
            'especialidad_id' => 'required|exists:especialidades,id',
            'estado_id' => 'required|exists:estados,id',
            'descripcion' => 'required',
            'direccion' => 'required',
            'fecha_servicio' => 'required|date',
            'tipo_urgencia' => 'required|in:estandar,urgente',
        ]);

        $especialidad = Especialidad::findOrFail($request->input('especialidad_id'));
        $precioBase = self::PRECIOS[$especialidad->nombre_especialidad] ?? $incidencia->precio_base;

        $incidencia->update([
            'especialidad_id' => $request->input('especialidad_id'),
            'estado_id' => $request->input('estado_id'),
            'descripcion' => $request->input('descripcion'),
            'direccion' => $request->input('direccion'),
            'fecha_servicio' => $request->input('fecha_servicio'),
            'tipo_urgencia' => $request->input('tipo_urgencia'),
            'precio_base' => $precioBase,
        ]);

        // Commission logic
        $estadoFinalizada = Estado::where('nombre_estado', 'Finalizada')->first()->id;

        // Create commission if transitioning TO Finalizada with gestora
        if ($incidencia->estado_id == $estadoFinalizada && $incidencia->gestora_id && $incidencia->precio_base > 0 && !Comision::where('incidencia_id', $id)->exists()) {
            $gestora = $incidencia->gestora;
            Comision::create([
                'gestora_id' => $incidencia->gestora_id,
                'incidencia_id' => $incidencia->id,
                'monto_base' => $incidencia->precio_base,
                'porcentaje_aplicado' => $gestora->porcentaje_comision,
                'monto_comision' => round($incidencia->precio_base * ($gestora->porcentaje_comision / 100), 2),
                'mes' => now()->startOfMonth()->toDateString(),
                'pagada' => false,
            ]);
        }

        // Revoke commission if transitioning FROM Finalizada
        if ($estadoAnterior == $estadoFinalizada && $incidencia->estado_id != $estadoFinalizada) {
            Comision::where('incidencia_id', $id)->delete();
        }

        return redirect('/admin/incidencias')->with('success', 'Incidencia actualizada correctamente.');
    }

    /**
     * Assign a tecnico to an incidencia and change estado to Asignada.
     */
    public function asignarTecnico(Request $request)
    {
        $request->validate([
            'incidencia_id' => 'required|exists:incidencias,id',
            'tecnico_id' => 'required|exists:tecnicos,id',
        ]);

        $incidencia = Incidencia::findOrFail($request->input('incidencia_id'));
        $estadoAsignada = Estado::where('nombre_estado', 'Asignada')->first();

        $incidencia->update([
            'tecnico_id' => $request->input('tecnico_id'),
            'estado_id' => $estadoAsignada->id,
        ]);

        return redirect('/admin/incidencias')->with('success', 'Técnico asignado correctamente.');
    }

    /**
     * Cancel an incidencia (no 48h restriction for admin).
     */
    public function cancelarIncidencia(Request $request)
    {
        $request->validate([
            'incidencia_id' => 'required|exists:incidencias,id',
        ]);

        $incidencia = Incidencia::findOrFail($request->input('incidencia_id'));
        $estadoCancelada = Estado::where('nombre_estado', 'Cancelada')->first();

        $incidencia->update([
            'estado_id' => $estadoCancelada->id,
        ]);

        return redirect('/admin/incidencias')->with('success', 'Incidencia cancelada correctamente.');
    }

    /**
     * Calendar view with all incidencias.
     */
    public function calendario()
    {
        $incidencias = Incidencia::with(['estado', 'especialidad', 'tecnico', 'cliente'])
            ->orderBy('fecha_servicio', 'asc')
            ->get();

        $incidenciasJson = $incidencias->map(function ($i) {
            return [
                'localizador' => $i->localizador,
                'cliente_nombre' => $i->cliente->nombre ?? 'N/A',
                'nombre_especialidad' => $i->especialidad->nombre_especialidad ?? 'N/A',
                'fecha_servicio' => $i->fecha_servicio ? $i->fecha_servicio->format('Y-m-d H:i:s') : null,
                'tipo_urgencia' => $i->tipo_urgencia,
                'nombre_estado' => $i->estado->nombre_estado ?? '',
            ];
        });

        return view('admin.calendario', compact('incidencias', 'incidenciasJson'));
    }

    /**
     * List all empresas gestoras.
     */
    public function gestoras()
    {
        $gestoras = EmpresaGestora::all();

        return view('admin.gestoras', compact('gestoras'));
    }

    /**
     * Show form to create a new gestora.
     */
    public function crearGestora()
    {
        return view('admin.crear_gestora');
    }

    /**
     * Store a new gestora with validated data.
     */
    public function storeGestora(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'cif' => 'required|regex:/^[A-Z]\d{7}[A-Z0-9]$/|unique:empresas_gestoras,cif',
            'email' => 'required|email|unique:empresas_gestoras,email',
            'password' => 'required|min:6',
            'porcentaje_comision' => 'required|numeric|between:0,100',
        ]);

        EmpresaGestora::create([
            'nombre' => $request->input('nombre'),
            'cif' => $request->input('cif'),
            'direccion' => $request->input('direccion'),
            'telefono' => $request->input('telefono'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'porcentaje_comision' => $request->input('porcentaje_comision'),
            'activa' => true,
        ]);

        return redirect('/admin/gestoras')->with('success', 'Gestora creada correctamente.');
    }

    /**
     * Show edit form for a gestora.
     */
    public function editGestora($id)
    {
        $gestora = EmpresaGestora::findOrFail($id);

        return view('admin.editar_gestora', compact('gestora'));
    }

    /**
     * Update gestora data.
     */
    public function updateGestora(Request $request, $id)
    {
        $gestora = EmpresaGestora::findOrFail($id);

        $request->validate([
            'nombre' => 'required',
            'cif' => 'required|regex:/^[A-Z]\d{7}[A-Z0-9]$/|unique:empresas_gestoras,cif,' . $id,
            'email' => 'required|email|unique:empresas_gestoras,email,' . $id,
            'porcentaje_comision' => 'required|numeric|between:0,100',
        ]);

        $data = [
            'nombre' => $request->input('nombre'),
            'cif' => $request->input('cif'),
            'direccion' => $request->input('direccion'),
            'telefono' => $request->input('telefono'),
            'email' => $request->input('email'),
            'porcentaje_comision' => $request->input('porcentaje_comision'),
            'activa' => $request->has('activa') ? true : false,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $gestora->update($data);

        return redirect('/admin/gestoras')->with('success', 'Gestora actualizada correctamente.');
    }

    /**
     * Show all incidencias/comisiones for a specific gestora.
     */
    public function comisionesGestora($id)
    {
        $gestora = EmpresaGestora::findOrFail($id);

        $incidencias = Incidencia::where('gestora_id', $id)
            ->with(['estado', 'comision'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPendiente = Comision::where('gestora_id', $id)
            ->where('pagada', false)
            ->sum('monto_comision');

        return view('admin.comisiones_gestora', compact('gestora', 'incidencias', 'totalPendiente'));
    }

    /**
     * Monthly settlement view for all active gestoras.
     */
    public function liquidacionMensual()
    {
        $gestoras = EmpresaGestora::where('activa', true)->get();

        $mesActual = now()->startOfMonth()->toDateString();
        $totalGlobal = 0;

        $liquidaciones = $gestoras->map(function ($gestora) use ($mesActual, &$totalGlobal) {
            $totalMes = Comision::where('gestora_id', $gestora->id)
                ->where('pagada', false)
                ->where('mes', $mesActual)
                ->sum('monto_comision');

            $totalPendiente = Comision::where('gestora_id', $gestora->id)
                ->where('pagada', false)
                ->sum('monto_comision');

            $totalGlobal += $totalPendiente;

            return (object) [
                'gestora' => $gestora,
                'total_mes' => $totalMes,
                'total_pendiente' => $totalPendiente,
            ];
        });

        return view('admin.liquidacion_mensual', compact('liquidaciones', 'totalGlobal'));
    }

    /**
     * Mark all unpaid comisiones for a gestora in a given month as paid.
     */
    public function marcarPagada(Request $request, $id)
    {
        $gestora = EmpresaGestora::findOrFail($id);
        $mes = $request->input('mes', now()->startOfMonth()->toDateString());

        Comision::where('gestora_id', $id)
            ->where('pagada', false)
            ->where('mes', $mes)
            ->update(['pagada' => true]);

        return redirect()->back()->with('success', 'Comisiones de ' . $gestora->nombre . ' marcadas como pagadas.');
    }
}
