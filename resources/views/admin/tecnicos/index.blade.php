<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de técnicos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 bg-white shadow-sm sm:rounded-lg p-6 flex justify-between items-center border border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Plantilla de técnicos</h3>
                    <p class="text-gray-500 text-sm">Gestiona los técnicos que atenderán los servicios.</p>
                </div>
                <a href="{{ route('admin.tecnicos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition-colors">
                    Nuevo técnico
                </a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($tecnicos as $user)
                                @php
                                    $perfilTecnico = \App\Models\Tecnico::where('usuario_id', $user->id)->first();
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $user->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                        {{ $perfilTecnico ? $perfilTecnico->especialidad->nombre_especialidad : 'Sin Asignar' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $perfilTecnico && $perfilTecnico->disponible == 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $perfilTecnico && $perfilTecnico->disponible == 1 ? 'Alta' : 'Baja' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($perfilTecnico)
                                            <form action="{{ route('admin.tecnicos.toggle', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="font-bold transition-colors {{ $perfilTecnico->disponible == 1 ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800' }}">
                                                    {{ $perfilTecnico->disponible == 1 ? 'Dar de Baja' : 'Dar de Alta' }}
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No hay técnicos dados de alta.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>