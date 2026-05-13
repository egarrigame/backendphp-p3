<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear incidencia') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-lg mx-auto px-4">

            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">

                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-medium text-gray-900">Nueva incidencia</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Comunidad: <strong class="font-medium text-gray-700">{{ $comunidad->nombre }}</strong></p>
                </div>

                <div class="px-6 py-6">
                    <form action="{{ route('gestora.incidencias.store', $comunidad->id) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-600 mb-1.5">Descripción</label>
                            <textarea
                                name="descripcion"
                                rows="4"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required
                                placeholder="Ej: Hay una fuga..."
                            ></textarea>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-600 mb-1.5">Especialidad</label>
                            <select
                                name="especialidad_id"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required>
                                <option value="" disabled selected>Seleccione el tipo de avería...</option>
                                @foreach($especialidades as $especialidad)
                                    <option value="{{ $especialidad->id }}">
                                        {{ $especialidad->nombre_especialidad }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-600 mb-1.5">Fecha para el servicio</label>
                            <input
                                type="date"
                                name="fecha_servicio"
                                id="fecha_servicio"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required
                                min="{{ date('Y-m-d') }}"
                            >
                            <p class="text-xs text-gray-400 mt-1.5">Recuerde el margen de 48h para servicios estándar.</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-600 mb-1.5">Tipo de urgencia</label>
                            <select
                                name="tipo_urgencia"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required>
                                <option value="Estándar">Estándar</option>
                                <option value="Urgente">Urgente</option>
                            </select>
                        </div>

                        <div class="flex justify-end items-center gap-3 pt-5 border-t border-gray-100">
                            <a href="{{ route('gestora.dashboard') }}"
                               class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">
                                Cancelar
                            </a>
                            <button
                                type="submit"
                                class="text-sm font-medium bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg transition">
                                Crear
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>