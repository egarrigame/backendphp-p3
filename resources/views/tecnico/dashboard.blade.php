<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Servicios asignados') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($incidencias as $incidencia)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        
                        <div class="bg-blue-600 px-4 py-3 flex justify-between items-center text-white">
                            <span class="font-bold">#{{ $incidencia->localizador }}</span>
                            <span class="text-sm bg-blue-800 px-2 py-1 rounded">{{ \Carbon\Carbon::parse($incidencia->fecha_servicio)->format('d/m/Y') }}</span>
                        </div>

                        <div class="p-5">
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Dirección</p>
                            <p class="font-bold text-gray-800 mb-4">{{ $incidencia->direccion }}</p>
                            
                            @if($incidencia->comunidad)
                                <p class="text-sm text-gray-600 mb-4">Comunidad: {{ $incidencia->comunidad->nombre }}</p>
                            @endif

                            <div class="bg-gray-50 border border-gray-100 rounded p-3 mb-4 text-sm text-gray-700">
                                <strong>Avería ({{ $incidencia->especialidad->nombre_especialidad ?? 'Gral' }}):</strong><br>
                                {{ $incidencia->descripcion }}
                            </div>

                            <div class="flex items-center justify-between border-t pt-4 mt-2">
                                @if($incidencia->tipo_urgencia == 'Urgente')
                                    <span class="px-2 py-1 text-xs font-bold rounded bg-red-100 text-red-800">Urgente</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-bold rounded bg-gray-100 text-gray-800">Estándar</span>
                                @endif

                                <form action="{{ route('tecnico.servicios.completar', $incidencia->id) }}" method="POST" onsubmit="return confirm('¿Confirmas que has finalizado esta avería?');">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors text-sm">
                                        Marcar terminado
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-12 text-center rounded-xl shadow-sm border border-gray-200">
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay servicios pendientes</h3>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>