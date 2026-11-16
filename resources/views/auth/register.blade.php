<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Crear Cuenta</h2>
        <p class="text-gray-600">Únete a nuestra comunidad de voluntarios</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nombre Completo')" />
            <x-text-input id="name" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Correo Electrónico UNIFRANZ')" />
            <x-text-input id="email" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="ejemplo@est.unifranz.edu.bo" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <p class="text-xs text-gray-500 mt-1">
                Extensiones válidas: 
                <span class="font-medium">@est.unifranz.edu.bo</span> (estudiantes), 
                <span class="font-medium">@docente.unifranz.edu.bo</span> (docentes), 
                <span class="font-medium">@unifranz.edu.bo</span> (administrativos)
            </p>
        </div>

        <!-- Phone -->
        <div>
            <x-input-label for="phone" :value="__('Teléfono (Obligatorio)')" />
            <div class="flex mt-1">
                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-lg">
                    🇧🇴 +591
                </span>
                <x-text-input id="phone" class="flex-1 rounded-none rounded-r-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200" type="tel" name="phone" :value="old('phone')" required autocomplete="tel" maxlength="8" pattern="[0-9]{8}" placeholder="12345678" oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            <p class="text-xs text-gray-500 mt-1">Ingresa tu número de teléfono (8 dígitos, solo números)</p>
        </div>

        <!-- Cod Est (Required) -->
        <div>
            <x-input-label for="cod_est" :value="__('Código Estudiantil (Obligatorio)')" />
            <x-text-input id="cod_est" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200" type="text" name="cod_est" :value="old('cod_est')" required autocomplete="off" />
            <x-input-error :messages="$errors->get('cod_est')" class="mt-2" />
            <p class="text-xs text-gray-500 mt-1">Tu código estudiantil será tu contraseña inicial para acceder al sistema.</p>
        </div>

        <div class="flex flex-col space-y-4">
            <x-primary-button class="w-full justify-center">
                {{ __('Crear Cuenta') }}
            </x-primary-button>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    ¿Ya tienes cuenta?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>
        </div>
    </form>
</x-guest-layout>
