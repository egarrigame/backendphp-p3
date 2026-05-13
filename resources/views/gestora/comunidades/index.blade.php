<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de gestora') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold">Mis comunidades</h3>
                    <p class="text-gray-600">Gestiona tus fincas y sus incidencias.</p>
                </div>
                <a href="{{ route('gestora.comunidades.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Nueva comunidad
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dirección</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zona</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($comunidades as $comunidad)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $comunidad->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $comunidad->direccion }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $comunidad->zona->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('gestora.incidencias.create', $comunidad->id) }}" class="text-red-600 hover:text-red-900 font-bold border border-red-200 bg-red-50 px-3 py-1 rounded-md text-sm">
                                            Crear incidencia
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        No hay comunidades registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>