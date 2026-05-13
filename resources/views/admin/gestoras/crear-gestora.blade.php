<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear gestora') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-lg mx-auto px-4">

            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">

                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-medium text-gray-900">Datos de la gestora</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Rellena los campos para crear una nueva gestora.</p>
                </div>

                <div class="px-6 py-6">
                    <form action="{{ route('admin.gestoras.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-600 mb-1.5">Nombre</label>
                            <input
                                type="text"
                                name="nombre"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required
                                placeholder="Ej: Fincas Mediterráneo">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-600 mb-1.5">Email</label>
                            <input
                                type="email"
                                name="email"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required
                                placeholder="empresa@ejemplo.com">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-600 mb-1.5">Teléfono</label>
                            <input
                                type="text"
                                name="telefono"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required
                                placeholder="600000000">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-600 mb-1.5">Contraseña</label>
                            <input
                                type="password"
                                name="password"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required>
                            <p class="text-xs text-gray-400 mt-1.5">Mínimo 6 caracteres.</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-600 mb-1.5">Comisión</label>
                            <input
                                type="number"
                                step="0.01"
                                name="comision_pactada"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="0.00"
                                required>
                        </div>

                        <div class="flex justify-end items-center gap-3 pt-5 border-t border-gray-100">
                            <a href="{{ route('admin.dashboard') }}"
                               class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">
                                Cancelar
                            </a>
                            <button
                                type="submit"
                                class="text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
                                Guardar
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>