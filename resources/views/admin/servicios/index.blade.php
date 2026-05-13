<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de servicios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 bg-white shadow-sm sm:rounded-lg p-6 flex justify-between items-center border border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Todos los avisos</h3>
                    <p class="text-gray-500 text-sm">Panel global para asignar operarios y modificar servicios.</p>
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition-colors">
                    Alta Manual de aviso
                </button>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referencia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Técnico</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($incidencias as $incidencia)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-bold text-blue-600">{{ $incidencia->localizador }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($incidencia->fecha_servicio)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $incidencia->cliente->nombre ?? 'Desconocido' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $incidencia->especialidad->nombre_especialidad ?? 'General' }}
                                    @if($incidencia->tipo_urgencia == 'Urgente')
                                        <span class="ml-2 px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded bg-red-100 text-red-800">24h</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($incidencia->tecnico)
                                        <span class="text-green-600">{{ $incidencia->tecnico->usuario->nombre ?? $incidencia->tecnico->nombre_completo }}</span>
                                    @else
                                        <span class="text-orange-500">Sin asignar</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    {{ number_format($incidencia->precio_base, 2, ',', '.') }}€
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $incidencia->estado->nombre_estado ?? 'Pendiente' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.servicios.show', $incidencia->id) }}" class="text-blue-600 hover:text-blue-900 font-bold mr-3 border border-blue-200 bg-blue-50 px-3 py-1 rounded-md text-sm">
                                        Ver / Asignar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">No hay servicios registrados en el sistema.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>