<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Nuevo Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-white via-orange-50 to-red-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900 overflow-hidden shadow-2xl sm:rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6 lg:p-8 bg-gradient-to-r from-orange-500 via-red-500 to-pink-500 border-b border-white/20">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900 flex items-center">
                                <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Crear Nuevo Usuario
                            </h3>
                            <p class="mt-2 text-gray-700 text-lg">
                                Registra un nuevo usuario en el sistema de voluntariado
                            </p>
                        </div>
                    </div>
                <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="bg-gradient-to-br from-white to-gray-50 rounded-lg p-8 border border-gray-200 shadow-inner">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="mb-6">
                        <h4 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Información del Usuario
                        </h4>
                    </div>

                    <!-- Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Nombre Completo *
                        </label>
                        <input type="text" name="name" id="name" class="shadow appearance-none border-2 border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200" value="{{ old('name') }}" required autofocus autocomplete="name">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="mb-6">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                            Correo Electrónico UNIFRANZ *
                        </label>
                        <input type="email" name="email" id="email" class="shadow appearance-none border-2 border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200" value="{{ old('email') }}" required autocomplete="username" placeholder="ejemplo@est.unifranz.edu.bo">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div class="mb-6">
                        <label for="role" class="block text-gray-700 text-sm font-bold mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"></path>
                            </svg>
                            Rol *
                        </label>
                        <select name="role" id="role" class="shadow appearance-none border-2 border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200" required>
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Selecciona un rol</option>
                            <option value="estudiante" {{ old('role') === 'estudiante' ? 'selected' : '' }}>👨‍🎓 Estudiante</option>

                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>👑 Administrador</option>
                        </select>
                        @error('role')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="mb-6">
                        <label for="phone" class="block text-gray-700 text-sm font-bold mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                            Teléfono *
                        </label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-lg">
                                🇧🇴 +591
                            </span>
                            <input type="tel" name="phone" id="phone" class="flex-1 rounded-none rounded-r-lg border-2 border-gray-300 shadow appearance-none py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200" value="{{ old('phone') }}" required autocomplete="tel" maxlength="8" pattern="[0-9]{8}" placeholder="12345678" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Ingresa el número de teléfono (8 dígitos, solo números)</p>
                    </div>

                    <!-- Cod Est -->
                    <div class="mb-6">
                        <label for="cod_est" class="block text-gray-700 text-sm font-bold mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zM8 6a2 2 0 114 0v1H8V6z" clip-rule="evenodd"></path>
                            </svg>
                            Código Estudiantil *
                        </label>
                        <input type="text" name="cod_est" id="cod_est" class="shadow appearance-none border-2 border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200" value="{{ old('cod_est') }}" required autocomplete="off">
                        @error('cod_est')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">El código estudiantil será la contraseña inicial si no se especifica otra.</p>
                    </div>



                    <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.users') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-300 transform hover:scale-105 shadow-lg">
                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-3 px-8 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-300 transform hover:scale-105 shadow-lg">
                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
