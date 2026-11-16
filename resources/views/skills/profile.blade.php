<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Mi Perfil de Habilidades') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Rastrea tu progreso y evolución en diferentes áreas
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-white via-blue-50 to-indigo-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Skills Overview -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Mis Habilidades
                            </h3>

                            <div class="space-y-4">
                                @forelse($userSkills as $skill)
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-4 rounded-xl border border-blue-200 dark:border-blue-700">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center space-x-3">
                                                <span class="text-2xl">{{ $skill->getEmoji() }}</span>
                                                <div>
                                                    <h4 class="font-bold text-gray-800 dark:text-gray-200">{{ $skill->name }}</h4>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $skill->description }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-lg font-bold text-blue-600 dark:text-blue-400">Nivel {{ $skill->pivot->level }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $skill->pivot->experience_points }} XP</div>
                                            </div>
                                        </div>

                                        <!-- Progress Bar -->
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-2">
                                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-300"
                                                 style="width: {{ ($skill->pivot->experience_points % 100) }}%"></div>
                                        </div>

                                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                            <span>{{ $skill->pivot->experience_points % 100 }}/100 XP para siguiente nivel</span>
                                            <span>Última actualización: {{ $skill->pivot->last_updated->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400">No has configurado tus habilidades aún.</p>
                                        <a href="{{ route('skills.setup') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">Configurar habilidades</a>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">

                    <!-- Evolution Stats -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                Estadísticas de Evolución
                            </h3>

                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Total de transiciones:</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $evolutionStats['total_transitions'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Habilidades mejoradas:</span>
                                    <span class="font-bold text-green-600">{{ $evolutionStats['level_ups'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">XP total ganado:</span>
                                    <span class="font-bold text-blue-600">{{ $evolutionStats['total_xp_gained'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Tasa de mejora:</span>
                                    <span class="font-bold text-purple-600">{{ $evolutionStats['improvement_rate'] ?? 0 }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recommendations -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                Recomendaciones
                            </h3>

                            @if($recommendations->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recommendations->take(3) as $activity)
                                        <div class="p-3 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg border border-purple-200 dark:border-purple-700">
                                            <h4 class="font-bold text-gray-800 dark:text-gray-200 text-sm">{{ $activity->name }}</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ Str::limit($activity->description, 60) }}</p>
                                            <div class="flex justify-between items-center mt-2">
                                                <span class="text-xs text-purple-600 dark:text-purple-400">{{ $activity->start_date?->format('d/m/Y') }}</span>
                                                <a href="{{ route('activities.show', $activity) }}" class="text-xs text-blue-600 hover:text-blue-800">Ver más</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-4 text-center">
                                    <a href="{{ route('skills.recommendations') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-600 text-white text-sm font-medium rounded-lg hover:from-purple-600 hover:to-pink-700 transition-all duration-200">
                                        Ver todas las recomendaciones
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-sm text-center">No hay recomendaciones disponibles en este momento.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Acciones Rápidas</h3>

                            <div class="space-y-3">
                                <a href="{{ route('activities.index') }}" class="block w-full text-left px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        Explorar Actividades
                                    </div>
                                </a>

                                <a href="{{ route('calendar.index') }}" class="block w-full text-left px-4 py-3 bg-gradient-to-r from-green-500 to-teal-600 text-white rounded-lg hover:from-green-600 hover:to-teal-700 transition-all duration-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Ver Calendario
                                    </div>
                                </a>

                                <a href="{{ route('skills.recommendations') }}" class="block w-full text-left px-4 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-lg hover:from-purple-600 hover:to-pink-700 transition-all duration-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                        </svg>
                                        Más Recomendaciones
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
