<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle del aviso') }} <span class="text-blue-600">#{{ $incidencia->localizador }}</span>
            </h2>
            <a href="{{ route('admin.servicios.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">← Volver al listado</a>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-2xl mx-auto px-4 space-y-4">

            {{-- Tarjeta: Datos del servicio --}}
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">

                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-start">
                    <div>
                        <h3 class="text-base font-medium text-gray-900">Datos del servicio</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Solicitado por: <strong class="font-medium text-gray-700">{{ $incidencia->cliente->nombre ?? 'Desconocido' }}</strong></p>
                    </div>
                    @php
                        $estado = $incidencia->estado->nombre_estado ?? 'Pendiente';
                        $estadoClasses = match(strtolower($estado)) {
                            'pendiente'  => 'bg-yellow-50 text-yellow-700 border border-yellow-200',
                            'completado',
                            'finalizado' => 'bg-green-50 text-green-700 border border-green-200',
                            'cancelado'  => 'bg-red-50 text-red-700 border border-red-200',
                            default      => 'bg-gray-100 text-gray-600 border border-gray-200',
                        };
                    @endphp
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $estadoClasses }}">
                        {{ $estado }}
                    </span>
                </div>

                <div class="px-6 py-5">
                    <div class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">

                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Fecha</p>
                            <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($incidencia->fecha_servicio)->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Especialidad</p>
                            <p class="text-gray-900 font-medium">{{ $incidencia->especialidad->nombre_especialidad ?? 'General' }}</p>
                        </div>

                        <div class="col-span-2">
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Dirección</p>
                            <p class="text-gray-900 font-medium">{{ $incidencia->direccion }}</p>
                            @if($incidencia->comunidad)
                                <p class="text-xs text-blue-600 mt-0.5">Finca vinculada: {{ $incidencia->comunidad->nombre }}</p>
                            @endif
                        </div>

                        <div class="col-span-2">
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Descripción</p>
                            <p class="text-gray-700 bg-gray-50 border border-gray-100 rounded-lg px-4 py-3 leading-relaxed">{{ $incidencia->descripcion }}</p>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Tarjeta: Asignación de técnico --}}
            <div class="bg-white border border-blue-100 rounded-xl overflow-hidden shadow-sm">

                <div class="px-6 py-4 border-b border-blue-100">
                    <h3 class="text-base font-medium text-gray-900">Asignación de técnico</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Selecciona el operario que atenderá este servicio.</p>
                </div>

                <div class="px-6 py-6">
                    <form action="{{ route('admin.servicios.asignar', $incidencia->id) }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-600 mb-1.5">Técnico</label>
                            <select
                                name="tecnico_id"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required>
                                <option value="" disabled {{ !$incidencia->tecnico_id ? 'selected' : '' }}>Selecciona un operario...</option>
                                @foreach($tecnicosDisponibles as $operario)
                                    <option value="{{ $operario->id }}" {{ $incidencia->tecnico_id == $operario->id ? 'selected' : '' }}>
                                        {{ $operario->usuario->nombre }} — {{ $operario->especialidad->nombre_especialidad ?? 'General' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end pt-2 border-t border-gray-100">
                            <button
                                type="submit"
                                class="text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
                                Guardar asignación
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>