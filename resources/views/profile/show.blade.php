<x-app-layout>
    <!-- Hero Profile Section -->
    <div class="relative bg-gradient-to-br from-unifranz-orange via-orange-500 to-red-500 overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="absolute inset-0">
            <svg class="absolute bottom-0 left-0 w-full h-24 text-white fill-current" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"></path>
            </svg>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center lg:items-start space-y-6 lg:space-y-0 lg:space-x-8">
                <!-- Profile Photo -->
                <div class="relative">
                    @if(Auth::user()->profile_photo_path)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Profile Photo" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-xl">
                    @else
                        <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center border-4 border-white shadow-xl">
                            <span class="text-white font-bold text-4xl">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-green-500 rounded-full border-4 border-white flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Profile Info -->
                <div class="flex-1 text-center lg:text-left">
                    <h1 class="text-4xl font-bold text-white mb-2">{{ Auth::user()->name }}</h1>
                    <p class="text-xl text-white opacity-90 mb-4">{{ Auth::user()->email }}</p>
                    <div class="flex flex-wrap justify-center lg:justify-start gap-3 mb-6">
                        <span class="px-4 py-2 bg-white bg-opacity-20 text-white rounded-full text-sm font-medium backdrop-blur-sm">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                        <span class="px-4 py-2 bg-white bg-opacity-20 text-white rounded-full text-sm font-medium backdrop-blur-sm">
                            {{ Auth::user()->is_active ? '🟢 Activo' : '🔴 Inactivo' }}
                        </span>
                        @if(Auth::user()->hasCompletedSkillsSetup())
                            <span class="px-4 py-2 bg-green-500 bg-opacity-80 text-white rounded-full text-sm font-medium backdrop-blur-sm">
                                ✅ Perfil Completo
                            </span>
                        @endif
                    </div>
                    
                    <!-- Enhanced Quick Stats -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4 text-center hover:bg-opacity-30 transition-all duration-300">
                            <div class="text-2xl font-bold text-white">{{ $activityStats['total'] }}</div>
                            <div class="text-sm text-white opacity-80">Actividades</div>
                            <div class="text-xs text-white opacity-60">{{ $activityStats['completed'] }} completadas</div>
                        </div>
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4 text-center hover:bg-opacity-30 transition-all duration-300">
                            <div class="text-2xl font-bold text-white">{{ Auth::user()->certificates()->count() }}</div>
                            <div class="text-sm text-white opacity-80">Certificados</div>
                            <div class="text-xs text-white opacity-60">Reconocimientos</div>
                        </div>
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4 text-center hover:bg-opacity-30 transition-all duration-300">
                            <div class="text-2xl font-bold text-white">{{ $skillInsights['total_skills'] }}</div>
                            <div class="text-sm text-white opacity-80">Habilidades</div>
                            <div class="text-xs text-white opacity-60">{{ $skillInsights['advanced_skills'] }} avanzadas</div>
                        </div>
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4 text-center hover:bg-opacity-30 transition-all duration-300">
                            <div class="text-2xl font-bold text-white">{{ $activityStats['points_earned'] }}</div>
                            <div class="text-sm text-white opacity-80">Puntos</div>
                            @if($userRanking)
                                <div class="text-xs text-white opacity-60">Rango {{ $userRanking->rank }}</div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-wrap justify-center lg:justify-start gap-3">
                        <button onclick="openEditModal()" class="px-6 py-3 bg-white text-unifranz-orange rounded-lg font-semibold hover:bg-gray-100 transition duration-300 shadow-lg">
                            ✏️ Editar Perfil
                        </button>
                        <button onclick="openPasswordModal()" class="px-6 py-3 bg-unifranz-black text-white rounded-lg font-semibold hover:bg-gray-800 transition duration-300 shadow-lg">
                            🔒 Cambiar Contraseña
                        </button>
                        @if(!Auth::user()->hasCompletedSkillsSetup())
                            <a href="{{ route('skills.setup') }}" class="px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition duration-300 shadow-lg">
                                🎯 Configurar Habilidades
                            </a>
                        @else
                            <a href="{{ route('markov.insights') }}" class="px-6 py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition duration-300 shadow-lg">
                                🤖 Ver Recomendaciones IA
                            </a>
                            <a href="{{ route('achievements.index') }}" class="px-6 py-3 bg-yellow-600 text-white rounded-lg font-semibold hover:bg-yellow-700 transition duration-300 shadow-lg">
                                🏆 Mis Logros
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Personal Information Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8 transform hover:scale-[1.02] transition-all duration-300">
                <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 px-8 py-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Información Personal</h3>
                            <p class="text-blue-100">Detalles de tu cuenta y configuración</p>
                        </div>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Personal Details -->
                        <div class="space-y-6">
                            <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                Datos Básicos
                            </h4>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Nombre Completo</p>
                                            <p class="font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Correo Electrónico</p>
                                            <p class="font-semibold text-gray-900">{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Rol en el Sistema</p>
                                            <p class="font-semibold text-gray-900">{{ ucfirst(Auth::user()->role) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 {{ Auth::user()->is_active ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 {{ Auth::user()->is_active ? 'text-green-600' : 'text-red-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                @if(Auth::user()->is_active)
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                @else
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                @endif
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Estado de la Cuenta</p>
                                            <p class="font-semibold {{ Auth::user()->is_active ? 'text-green-600' : 'text-red-600' }}">
                                                {{ Auth::user()->is_active ? '✅ Activo' : '❌ Inactivo' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="space-y-6">
                            <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                <span class="w-2 h-2 bg-purple-500 rounded-full mr-3"></span>
                                Acciones Rápidas
                            </h4>
                            <div class="space-y-4">
                                <button onclick="openEditModal()" class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-xl transition-all duration-300 group">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-semibold text-blue-700">Editar Información</p>
                                            <p class="text-sm text-blue-600">Actualiza tus datos personales</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-blue-500 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                
                                <button onclick="openPasswordModal()" class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-xl transition-all duration-300 group">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-semibold text-green-700">Cambiar Contraseña</p>
                                            <p class="text-sm text-green-600">Actualiza tu contraseña de acceso</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-green-500 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                
                                @if(!Auth::user()->hasCompletedSkillsSetup())
                                    <a href="{{ route('skills.setup') }}" class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 rounded-xl transition-all duration-300 group">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                                </svg>
                                            </div>
                                            <div class="text-left">
                                                <p class="font-semibold text-purple-700">Configurar Habilidades</p>
                                                <p class="text-sm text-purple-600">Completa tu perfil de habilidades</p>
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-purple-500 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skills Section -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8 transform hover:scale-[1.02] transition-all duration-300">
                <div class="bg-gradient-to-r from-purple-600 via-pink-600 to-indigo-600 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white">🎯 Mis Habilidades</h3>
                                <p class="text-purple-100">Desarrollo y progreso de competencias</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            @if(Auth::user()->hasCompletedSkillsSetup())
                                <a href="{{ route('skills.profile') }}" class="px-6 py-3 bg-white bg-opacity-20 text-white rounded-xl hover:bg-opacity-30 transition duration-300 text-sm font-semibold backdrop-blur-sm">
                                    📈 Ver Perfil Completo
                                </a>
                            @else
                                <a href="{{ route('skills.setup') }}" class="px-6 py-3 bg-white text-purple-600 rounded-xl hover:bg-purple-50 transition duration-300 text-sm font-semibold shadow-lg">
                                    ⚙️ Configurar Habilidades
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="p-8">
                    @if(Auth::user()->hasCompletedSkillsSetup())
                        @if($userSkills->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($userSkills as $skill)
                                    <div class="bg-gradient-to-br from-purple-50 via-pink-50 to-indigo-50 border-2 border-purple-200 rounded-2xl p-6 hover:shadow-xl hover:scale-105 transition-all duration-300 group">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                                                    <span class="text-white font-bold text-lg">{{ $skill->pivot->level }}</span>
                                                </div>
                                                <h4 class="font-bold text-gray-800 text-lg">{{ $skill->name }}</h4>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $skill->pivot->level)
                                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <div class="flex justify-between text-sm font-medium text-gray-700 mb-2">
                                                <span>🎯 Experiencia</span>
                                                <span class="text-purple-600">{{ $skill->pivot->experience_points }} XP</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                                @php
                                                    $maxXP = $skill->pivot->level * 100;
                                                    $progress = min(($skill->pivot->experience_points / $maxXP) * 100, 100);
                                                @endphp
                                                <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-indigo-500 h-3 rounded-full transition-all duration-500 group-hover:animate-pulse" style="width: {{ $progress }}%"></div>
                                            </div>
                                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                                <span>Nivel {{ $skill->pivot->level }}</span>
                                                <span>{{ $progress }}%</span>
                                            </div>
                                        </div>

                                        <p class="text-sm text-gray-600 leading-relaxed">{{ $skill->description }}</p>
                                        
                                        <div class="mt-4 pt-4 border-t border-purple-200">
                                            <div class="flex items-center justify-between text-xs text-gray-500">
                                                <span>📅 Última actualización</span>
                                                <span>{{ $skill->pivot->last_updated ? \Carbon\Carbon::parse($skill->pivot->last_updated)->diffForHumans() : 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-16">
                                <div class="w-24 h-24 bg-gradient-to-r from-purple-100 to-pink-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-12 h-12 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">🎯 No tienes habilidades configuradas</h3>
                                <p class="text-gray-600 mb-8 max-w-md mx-auto leading-relaxed">Configura tus habilidades para recibir recomendaciones personalizadas y comenzar tu journey de desarrollo profesional.</p>
                                <a href="{{ route('skills.setup') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    ✨ Configurar Habilidades
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-16">
                            <div class="w-24 h-24 bg-gradient-to-r from-purple-100 to-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                                <svg class="w-12 h-12 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-bold text-gray-900 mb-4">🚀 ¡Configura tus habilidades!</h3>
                            <p class="text-gray-600 mb-8 max-w-2xl mx-auto text-lg leading-relaxed">Completa tu perfil de habilidades para recibir recomendaciones personalizadas de actividades basadas en tus intereses y nivel de experiencia. ¡Es el primer paso hacia tu crecimiento profesional!</p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ route('skills.setup') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 font-bold shadow-xl hover:shadow-2xl transform hover:scale-105">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    ✨ Comenzar Configuración
                                </a>
                                <a href="{{ route('activities.index') }}" class="inline-flex items-center px-8 py-4 bg-white border-2 border-purple-300 text-purple-600 rounded-xl hover:bg-purple-50 transition-all duration-300 font-semibold">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver Actividades
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Achievements Section -->
            @if(count($achievements) > 0)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8 transform hover:scale-[1.02] transition-all duration-300">
                <div class="bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500 px-8 py-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">🏆 Mis Logros</h3>
                            <p class="text-yellow-100">Reconocimientos y medallas ganadas</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($achievements as $achievement)
                            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 border-2 border-yellow-200 rounded-xl p-4 text-center hover:shadow-lg transition-all duration-300 group">
                                <div class="text-3xl mb-2 group-hover:scale-110 transition-transform duration-300">{{ $achievement['icon'] }}</div>
                                <h4 class="font-bold text-gray-800 text-sm mb-1">{{ $achievement['name'] }}</h4>
                                <p class="text-xs text-gray-600">{{ $achievement['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Skill Development Progress -->
            @if($skillInsights['total_skills'] > 0)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8 transform hover:scale-[1.02] transition-all duration-300">
                <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 px-8 py-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">📈 Progreso de Habilidades</h3>
                            <p class="text-indigo-100">Tu desarrollo y crecimiento</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $skillInsights['advanced_skills'] }}</div>
                            <div class="text-sm text-gray-600">Avanzadas</div>
                        </div>
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $skillInsights['developing_skills'] }}</div>
                            <div class="text-sm text-gray-600">En Desarrollo</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">{{ $skillInsights['beginner_skills'] }}</div>
                            <div class="text-sm text-gray-600">Principiante</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ $skillInsights['total_experience'] }}</div>
                            <div class="text-sm text-gray-600">XP Total</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recommendations and Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Recommendations -->
                @if(isset($recommendations) && $recommendations->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden transform hover:scale-[1.02] transition-all duration-300">
                    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 px-8 py-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white">🎯 Recomendaciones para Ti</h3>
                                <p class="text-indigo-100">Actividades personalizadas según tu perfil</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($recommendations as $recommendation)
                                @if(isset($recommendation['activity']) && $recommendation['activity'])
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-200 hover:shadow-md transition duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $recommendation['activity']->name ?? 'Actividad sin nombre' }}</h4>
                                            <p class="text-sm text-gray-600">{{ $recommendation['activity']->start_date ? $recommendation['activity']->start_date->format('d/m/Y H:i') : 'Fecha por definir' }}</p>
                                            <p class="text-xs text-gray-500">{{ $recommendation['activity']->location ?? '' }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('activities.show', $recommendation['activity']) }}" class="px-3 py-1 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition duration-200">
                                        Ver Detalles
                                    </a>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('skills.recommendations') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                Ver todas las recomendaciones →
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Recent Activity -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden transform hover:scale-[1.02] transition-all duration-300">
                    <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 px-8 py-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white">📈 Actividad Reciente</h3>
                                <p class="text-green-100">Tu progreso y participación</p>
                            </div>
                        </div>
                    </div>
                <div class="p-6">
                    @php
                        $recentActivities = Auth::user()->activities()->latest()->take(5)->get();
                    @endphp

                    @if($recentActivities->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentActivities as $activity)
                                @if($activity)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <span class="text-lg">{{ $activity->getCategoryEmoji() }}</span>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $activity->name ?? 'Actividad sin nombre' }}</h4>
                                            <p class="text-sm text-gray-600">{{ $activity->created_at ? $activity->created_at->format('d/m/Y') : 'Fecha no disponible' }}</p>
                                            <p class="text-xs text-gray-500">+{{ $activity->getEstimatedPoints() }} puntos</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                            @if($activity->pivot && $activity->pivot->status === 'completed') bg-green-100 text-green-800
                                            @elseif($activity->pivot && $activity->pivot->status === 'joined') bg-blue-100 text-blue-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ $activity->pivot && $activity->pivot->status ? ucfirst(str_replace('_', ' ', $activity->pivot->status)) : 'Activo' }}
                                        </span>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('activities.index') }}" class="text-green-600 hover:text-green-800 font-medium">
                                Ver todas las actividades →
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay actividad reciente</h3>
                            <p class="text-gray-500 mb-4">Únete a actividades para comenzar a ganar experiencia.</p>
                            <a href="{{ route('activities.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                                Explorar Actividades
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Certificates and Donations -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Certificates -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden transform hover:scale-[1.02] transition-all duration-300">
                    <div class="bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500 px-8 py-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white">🏆 Mis Certificados</h3>
                                <p class="text-yellow-100">Reconocimientos y logros</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @php
                            $certificates = Auth::user()->certificates()->latest()->take(3)->get();
                        @endphp

                        @if($certificates->count() > 0)
                            <div class="space-y-4">
                                @foreach($certificates as $certificate)
                                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $certificate->activity_title }}</h4>
                                            <p class="text-sm text-gray-600">{{ $certificate->issue_date->format('d/m/Y') }}</p>
                                            <p class="text-xs text-gray-500">Horas: {{ $certificate->hours_volunteered }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                                #{{ $certificate->certificate_number }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ route('certificates.index') }}" class="text-yellow-600 hover:text-yellow-800 font-medium">
                                    Ver todos los certificados →
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes certificados</h3>
                                <p class="text-gray-500 mb-4">Completa actividades para obtener certificados.</p>
                                <a href="{{ route('activities.index') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition duration-200">
                                    Ver Actividades
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Donations -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden transform hover:scale-[1.02] transition-all duration-300">
                    <div class="bg-gradient-to-r from-red-500 via-pink-500 to-rose-500 px-8 py-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white">❤️ Mis Donaciones</h3>
                                <p class="text-red-100">Contribuciones y generosidad</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @php
                            $donations = Auth::user()->donations()->latest()->take(3)->get();
                        @endphp

                        @if($donations->count() > 0)
                            <div class="space-y-4">
                                @foreach($donations as $donation)
                                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $donation->title }}</h4>
                                            <p class="text-sm text-gray-600">{{ $donation->donation_date->format('d/m/Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst($donation->type) }} - {{ $donation->amount }} Bs.</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="px-2 py-1 rounded text-xs font-medium
                                                @if($donation->status === 'approved') bg-green-100 text-green-800
                                                @elseif($donation->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($donation->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ route('donations.index') }}" class="text-red-600 hover:text-red-800 font-medium">
                                    Ver todas las donaciones →
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No has realizado donaciones</h3>
                                <p class="text-gray-500 mb-4">Tu generosidad ayuda a la Fundación UniFránz.</p>
                                <a href="{{ route('donations.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                                    Hacer una Donación
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edición de Perfil -->
    <div id="editProfileModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Editar Información Personal</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('patch')

                    <!-- Profile Photo -->
                    <div>
                        <x-input-label for="profile_photo" value="Foto de Perfil" />
                        <div class="mt-2 flex items-center space-x-4">
                            @if($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile Photo" class="h-16 w-16 rounded-full object-cover">
                            @else
                                <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-gray-600 font-medium">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <input id="profile_photo" name="profile_photo" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF hasta 2MB</p>
                        <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                    </div>

                    <div>
                        <x-input-label for="name" value="Nombre" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Correo Electrónico" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <!-- Phone -->
                    <div>
                        <x-input-label for="phone" value="Teléfono" />
                        <div class="flex mt-1">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-lg">
                                🇧🇴 +591
                            </span>
                            @php
                                $phoneNumber = $user->phone;
                                if (str_starts_with($phoneNumber, '+591')) {
                                    $phoneNumber = substr($phoneNumber, 4);
                                }
                            @endphp
                            <x-text-input id="phone" name="phone" type="tel" class="flex-1 rounded-none rounded-r-lg" :value="old('phone', $phoneNumber)" maxlength="8" pattern="[0-9]{8}" placeholder="12345678" oninput="this.value = this.value.replace(/[^0-9]/g, '')" autocomplete="tel" />
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        <p class="text-xs text-gray-500 mt-1">Ingresa tu número de teléfono (8 dígitos, solo números)</p>
                    </div>

                    <!-- Bio -->
                    <div>
                        <x-input-label for="bio" value="Biografía" />
                        <textarea id="bio" name="bio" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" autocomplete="off">{{ old('bio', $user->bio) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>Guardar Cambios</x-primary-button>
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition duration-200">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Cambio de Contraseña -->
    <div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Cambiar Contraseña</h3>
                    <button onclick="closePasswordModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('put')

                    <div>
                        <x-input-label for="current_password" value="Contraseña Actual" />
                        <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                        <x-input-error class="mt-2" :messages="$errors->updatePassword->get('current_password')" />
                        @if(!Auth::user()->password_changed && Auth::user()->cod_est)
                            <p class="text-xs text-orange-600 mt-1">💡 Tu contraseña actual es: <code class="bg-orange-100 px-2 py-1 rounded">{{ Auth::user()->cod_est }}</code></p>
                        @endif
                    </div>

                    <div>
                        <x-input-label for="password" value="Nueva Contraseña" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password')" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" value="Confirmar Contraseña" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password_confirmation')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>Cambiar Contraseña</x-primary-button>
                        <button type="button" onclick="closePasswordModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition duration-200">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal() {
            document.getElementById('editProfileModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editProfileModal').classList.add('hidden');
        }

        function openPasswordModal() {
            document.getElementById('passwordModal').classList.remove('hidden');
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const editModal = document.getElementById('editProfileModal');
            const passwordModal = document.getElementById('passwordModal');

            if (event.target == editModal) {
                closeEditModal();
            }
            if (event.target == passwordModal) {
                closePasswordModal();
            }
        }

        // Show success messages
        @if(session('status'))
            // Show success notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            notification.innerHTML = '✅ {{ session('status') }}';
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        @endif
    </script>
</x-app-layout>
