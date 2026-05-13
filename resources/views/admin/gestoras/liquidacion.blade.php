<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Control de Pagos:') }} <span class="text-blue-600">{{ $gestora->nombre }}</span>
            </h2>
            <a href="{{ route('admin.gestoras.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                &larr; Volver al listado de gestoras
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 bg-white p-8 rounded-2xl shadow-lg border border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-600">Importe Total a Liquidar</h3>
                    <p class="text-sm text-gray-500">Basado en todos los servicios con estado <span class="font-bold text-green-600">Finalizada</span>.</p>
                </div>
                <div class="text-right">
                    <span class="text-5xl font-extrabold text-blue-600">
                        {{ number_format($totalDeuda, 2, ',', '.') }}€
                    </span>
                    <p class="text-sm text-gray-400 mt-1 uppercase tracking-wider font-bold">Cierre de mes pendiente</p>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h4 class="font-bold text-gray-800 uppercase text-xs tracking-widest">Desglose de Servicios Realizados</h4>
                </div>
                
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha / Ref</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comunidad / Dirección</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Base</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider text-blue-600">Comisión ({{ $gestora->comision_pactada }}%)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm">
                        @forelse ($servicios as $servicio)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($servicio->fecha_servicio)->format('d/m/Y') }}</div>
                                    <div class="text-xs text-blue-600 font-mono">#{{ $servicio->localizador }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">{{ $servicio->comunidad->nombre ?? 'Comunidad General' }}</div>
                                    <div class="text-xs text-gray-500">{{ $servicio->direccion }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <span class="font-medium">{{ $servicio->especialidad->nombre_especialidad ?? 'General' }}</span>
                                    <br>
                                    <span class="text-xs {{ $servicio->tipo_urgencia == 'Urgente' ? 'text-red-500 font-bold' : 'text-gray-400' }}">
                                        {{ $servicio->tipo_urgencia }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-medium text-gray-900">
                                    {{ number_format($servicio->precio_base, 2, ',', '.') }}€
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-green-700 text-base">
                                    {{ number_format($servicio->comision_calculada, 2, ',', '.') }}€
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">
                                    No hay servicios finalizados pendientes de liquidar para esta gestora.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8 flex justify-end">
                <button onclick="window.print()" class="bg-gray-800 hover:bg-black text-white font-bold py-3 px-8 rounded-lg shadow-lg transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2-2v4h6z"></path></svg>
                    Imprimir Liquidación
                </button>
            </div>

        </div>
    </div>
</x-app-layout>