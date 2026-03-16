<div>
    @if ($showModal)
        <!-- Overlay oscuro de fondo -->
        <div class="fixed inset-0 bg-black/50 z-40" style="background-color: rgba(0, 0, 0, 0.5);"></div>

        <!-- Modal contenedor -->
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-auto p-8">
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Crear tu Empresa</h2>
                    <p class="text-gray-600 text-sm">Completa la información básica de tu empresa para comenzar</p>
                </div>

                <!-- Formulario -->
                <form wire:submit="submit" class="space-y-4">
                    <!-- Nombre de Empresa -->
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre de la Empresa
                        </label>
                        <input
                            type="text"
                            id="company_name"
                            wire:model="company_name"
                            placeholder="ej: Mi Empresa SL"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required
                        />
                        @error('company_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Industria -->
                    <div>
                        <label for="industry" class="block text-sm font-medium text-gray-700 mb-1">
                            Industria
                        </label>
                        <input
                            type="text"
                            id="industry"
                            wire:model="industry"
                            placeholder="ej: Tecnología, Retail, Servicios"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required
                        />
                        @error('industry')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- País -->
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">
                            País
                        </label>
                        <input
                            type="text"
                            id="country"
                            wire:model="country"
                            placeholder="ej: España, México, Colombia"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required
                        />
                        @error('country')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Error general -->
                    @if ($errors->has('submit'))
                        <div class="bg-red-50 border border-red-200 rounded-md p-3">
                            <p class="text-red-700 text-sm">{{ $errors->first('submit') }}</p>
                        </div>
                    @endif

                    <!-- Botón Submit -->
                    <div class="mt-6">
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span wire:loading.remove>Crear Empresa</span>
                            <span wire:loading>
                                <span class="inline-block animate-spin">⌛</span> Creando...
                            </span>
                        </button>
                    </div>
                </form>

                <!-- Footer info -->
                <p class="text-xs text-gray-500 text-center mt-4">
                    Puedes editar esta información más tarde en los ajustes de la empresa
                </p>
            </div>
        </div>
    @endif
</div>
