@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-purple-600 to-orange-500">
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        <div class="relative max-w-7xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="bg-white/20 backdrop-blur-sm rounded-full p-4">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">
                    ¡Hola, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-lg md:text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                    Bienvenido a tu plataforma de voluntariado. Haz la diferencia, gana puntos y desbloquea logros increíbles.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('activities.index') }}" class="bg-white text-blue-600 px-6 py-3 rounded-xl font-semibold hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        🎯 Explorar Actividades
                    </a>
                    <a href="{{ route('achievements.index') }}" class="bg-yellow-400 text-yellow-900 px-6 py-3 rounded-xl font-semibold hover:bg-yellow-300 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        🏆 Mis Logros
                    </a>
                    @if(!Auth::user()->hasCompletedSkillsSetup())
                    <a href="{{ route('skills.setup') }}" class="bg-green-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-green-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        ⚡ Configurar Habilidades
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Personal Stats Section -->
    <div class="-mt-8 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-blue-600 mb-1">{{ Auth::user()->points ?? 0 }}</div>
                            <div class="text-gray-600 text-sm">Mis Puntos</div>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-green-600 mb-1">{{ Auth::user()->participatingActivities()->count() }}</div>
                            <div class="text-gray-600 text-sm">Actividades</div>
                        </div>
                        <div class="bg-green-100 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            @php
                                $userRanking = Auth::user()->getOrCreateRanking();
                                $achievementsCount = $userRanking->getUnlockedAchievementsCount();
                            @endphp
                            <div class="text-3xl font-bold text-purple-600 mb-1">{{ $achievementsCount }}/17</div>
                            <div class="text-gray-600 text-sm">Logros</div>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            @php
                                $ranking = Auth::user()->getOrCreateRanking();
                                $position = $ranking ? \App\Models\Ranking::whereHas('user', function($query) {
                                    $query->where('role', '!=', 'admin')->where('is_active', true);
                                })->where('total_points', '>', $ranking->total_points)->count() + 1 : 'N/A';
                            @endphp
                            <div class="text-3xl font-bold text-orange-600 mb-1">#{{ $position }}</div>
                            <div class="text-gray-600 text-sm">Mi Ranking</div>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Acciones Rápidas</h2>
                <p class="text-lg text-gray-600">Todo lo que necesitas al alcance de un clic</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="group bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="text-white text-5xl mb-4">🎯</div>
                    <h3 class="text-xl font-bold text-white mb-3">Actividades</h3>
                    <p class="text-blue-100 mb-4 text-sm">Explora y únete a actividades de voluntariado</p>
                    <a href="{{ route('activities.index') }}" class="inline-flex items-center text-white font-semibold hover:text-blue-200 transition-colors">
                        Explorar <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </a>
                </div>

                <div class="group bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="text-white text-5xl mb-4">🏆</div>
                    <h3 class="text-xl font-bold text-white mb-3">Mis Logros</h3>
                    <p class="text-purple-100 mb-4 text-sm">Revisa tus logros y progreso</p>
                    <a href="{{ route('achievements.index') }}" class="inline-flex items-center text-white font-semibold hover:text-purple-200 transition-colors">
                        Ver Logros <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </a>
                </div>

                <div class="group bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="text-white text-5xl mb-4">📊</div>
                    <h3 class="text-xl font-bold text-white mb-3">Rankings</h3>
                    <p class="text-green-100 mb-4 text-sm">Compite con otros voluntarios</p>
                    <a href="{{ route('rankings.index') }}" class="inline-flex items-center text-white font-semibold hover:text-green-200 transition-colors">
                        Ver Rankings <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </a>
                </div>

                <div class="group bg-gradient-to-br from-orange-500 to-orange-600 p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="text-white text-5xl mb-4">💝</div>
                    <h3 class="text-xl font-bold text-white mb-3">Donaciones</h3>
                    <p class="text-orange-100 mb-4 text-sm">Contribuye a causas importantes</p>
                    <a href="{{ route('donations.index') }}" class="inline-flex items-center text-white font-semibold hover:text-orange-200 transition-colors">
                        Donar <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </a>
                </div>

                <div class="group bg-gradient-to-br from-indigo-500 to-indigo-600 p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="text-white text-5xl mb-4">🤖</div>
                    <h3 class="text-xl font-bold text-white mb-3">Recomendaciones</h3>
                    <p class="text-indigo-100 mb-4 text-sm">Actividades personalizadas para ti</p>
                    @if(!auth()->user()->isAdmin())
                    <a href="{{ route('markov.insights') }}" class="inline-flex items-center text-white font-semibold hover:text-indigo-200 transition-colors">
                        Ver Insights <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </a>
                    @else
                    <span class="text-indigo-200 text-sm">Solo para voluntarios</span>
                    @endif
                </div>

                <div class="group bg-gradient-to-br from-teal-500 to-teal-600 p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="text-white text-5xl mb-4">👤</div>
                    <h3 class="text-xl font-bold text-white mb-3">Mi Perfil</h3>
                    <p class="text-teal-100 mb-4 text-sm">Gestiona tu información personal</p>
                    <a href="{{ route('profile.show') }}" class="inline-flex items-center text-white font-semibold hover:text-teal-200 transition-colors">
                        Ver Perfil <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Actividades Completadas -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Mis Actividades Completadas</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @php
                        $completedActivities = Auth::user()->participatingActivities()->withPivot('status', 'completed_at', 'points_earned')->wherePivot('status', 'completed')->orderByPivot('completed_at', 'desc')->take(5)->get();
                    @endphp
                    @forelse($completedActivities as $activity)
                        <div class="p-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-green-100 rounded-lg">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $activity->title ?? $activity->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ Str::limit($activity->description, 60) }}</p>
                                        <p class="text-xs text-gray-500">Completada: {{ $activity->pivot->completed_at ? \Carbon\Carbon::parse($activity->pivot->completed_at)->format('d/m/Y') : 'Fecha no disponible' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        ✓ Completada
                                    </span>
                                    @if($activity->pivot->points_earned)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            +{{ $activity->pivot->points_earned }} pts
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-500">Aún no has completado ninguna actividad</p>
                            <p class="text-sm text-gray-400 mt-1">¡Participa en actividades para ganar puntos!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Panel -->
    @if(Auth::user()->role === 'admin')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-2xl p-8 shadow-xl">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Panel Administrativo</h3>
                    <p class="text-red-100">Gestiona el sistema de voluntariado</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="bg-white/20 hover:bg-white/30 text-white px-6 py-4 rounded-xl text-center font-semibold transition-all duration-300 backdrop-blur-sm">
                        📊 Dashboard Admin
                    </a>
                    <a href="{{ route('admin.users') }}" class="bg-white/20 hover:bg-white/30 text-white px-6 py-4 rounded-xl text-center font-semibold transition-all duration-300 backdrop-blur-sm">
                        👥 Gestionar Usuarios
                    </a>
                    <a href="{{ route('admin.reports') }}" class="bg-white/20 hover:bg-white/30 text-white px-6 py-4 rounded-xl text-center font-semibold transition-all duration-300 backdrop-blur-sm">
                        📈 Ver Reportes
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Password Change Prompt -->
    @if(session('show_password_change_prompt'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <div class="bg-gradient-to-r from-yellow-400 to-orange-400 rounded-2xl p-6 shadow-xl">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-white mb-2">🔐 Seguridad Importante</h3>
                    <p class="text-white/90 mb-4">
                        Tu contraseña actual es: <code class="bg-white/20 px-3 py-1 rounded-lg font-mono">{{ session('default_password') }}</code>
                        <br>
                        Por tu seguridad, te recomendamos cambiarla por una personal.
                    </p>
                    <a href="{{ route('profile.show') }}" class="inline-flex items-center bg-white text-orange-600 px-4 py-2 rounded-lg font-semibold hover:bg-orange-50 transition-colors">
                        Cambiar Contraseña →
                    </a>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.parentElement.style.display='none'" class="text-white/70 hover:text-white transition-colors">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
