<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Resumen de Comisiones y Liquidaciones') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 bg-white p-8 rounded-2xl shadow-lg border border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-600">Total Comisiones Acumuladas</h3>
                    <p class="text-sm text-gray-500 mb-1">Correspondiente a servicios finalizados.</p>
                </div>
                <div class="text-right">
                    <span class="text-5xl font-extrabold text-blue-600">
                        {{ number_format($totalComisiones, 2, ',', '.') }}€
                    </span>
                    <p class="text-sm text-green-600 font-bold">Pendiente de liquidar a final de mes</p>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h4 class="font-bold text-gray-800">Desglose de Servicios Realizados</h4>
                </div>
                
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha / Ref</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comunidad / Dirección</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Precio base</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Comisión</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm">
                        @forelse ($serviciosRealizados as $servicio)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($servicio->fecha_servicio)->format('d/m/Y') }}</div>
                                    <div class="text-xs text-blue-600">#{{ $servicio->localizador }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">{{ $servicio->comunidad->nombre ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $servicio->direccion }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $servicio->especialidad->nombre_especialidad ?? 'General' }}
                                    <span class="text-xs {{ $servicio->tipo_urgencia == 'Urgente' ? 'text-red-600' : 'text-gray-400' }}">
                                        ({{ $servicio->tipo_urgencia }})
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-medium text-gray-900">
                                    {{ number_format($servicio->precio_base, 2, ',', '.') }}€
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="font-bold text-green-700 text-lg">
                                        {{ number_format($servicio->comision_calculada, 2, ',', '.') }}€
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        ({{ number_format($servicio->porcentaje_comision, 0) }}% de comisión)
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    Aún no hay servicios finalizados cargados a su cuenta.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>