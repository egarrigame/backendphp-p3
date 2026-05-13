<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de administración') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Gestión gestoras</h3>
                <p class="text-gray-600 mb-4">Alta de gestoras y asignación de comisiones.</p>
                <a href="{{ route('admin.gestoras.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Nueva gestora </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Gestoras activas</h3>
                    
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comisión</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">A Liquidar</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($gestoras as $gestora)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $gestora->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $gestora->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $gestora->telefono }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-green-600">{{ $gestora->comision_pactada ?? '0' }}%</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-green-600 text-lg">
                                        {{ number_format($gestora->deuda_total, 2, ',', '.') }}€
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.gestoras.liquidar', $gestora->id) }}" class="text-blue-600 hover:text-blue-900 font-bold bg-blue-50 border border-blue-200 px-3 py-1 rounded-md">
                                            Ver Liquidación
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay gestoras todavía.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>