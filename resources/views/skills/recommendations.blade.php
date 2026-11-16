<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Recomendaciones Personalizadas') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Actividades sugeridas basadas en tus habilidades y evolución
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-white via-purple-50 to-pink-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="p-8 text-gray-900 dark:text-gray-100">

                    @if($recommendations->count() > 0)
                        <!-- Recommendations Header -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 p-6 rounded-xl border border-purple-200 dark:border-purple-700 mb-8">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-lg">🎯</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-purple-800 dark:text-purple-200 mb-2">Recomendaciones</h3>
                                    <p class="text-sm text-purple-700 dark:text-purple-300">
                                        Estas recomendaciones se generan analizando tu evolución de habilidades
                                        y predicen qué actividades te ayudarán a crecer profesionalmente.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Recommendations Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($recommendations as $activity)
                                <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 p-6 rounded-xl border border-gray-200 dark:border-gray-600 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-2">{{ $activity->name }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ Str::limit($activity->description, 100) }}</p>
                                        </div>
                                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center flex-shrink-0 ml-4">
                                            <span class="text-white text-lg">{{ $activity->getCategoryEmoji() }}</span>
                                        </div>
                                    </div>

                                    <!-- Activity Details -->
                                    <div class="space-y-2 mb-4">
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $activity->start_date?->format('d/m/Y H:i') ?: 'Fecha por confirmar' }}
                                        </div>

                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $activity->location ?: 'Ubicación por confirmar' }}
                                        </div>

                                        @if($activity->max_participants)
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                                </svg>
                                                Máximo {{ $activity->max_participants }} participantes
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Skills Match -->
                                    @if($activity->skills->count() > 0)
                                        <div class="mb-4">
                                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Habilidades que desarrollarás:</h4>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($activity->skills->take(3) as $skill)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                                        {{ $skill->getEmoji() }} {{ $skill->name }}
                                                    </span>
                                                @endforeach
                                                @if($activity->skills->count() > 3)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                                        +{{ $activity->skills->count() - 3 }} más
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="flex space-x-2">
                                        <a href="{{ route('activities.show', $activity) }}" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm font-medium rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Ver Detalles
                                        </a>

                                        @if($activity->canUserJoin(auth()->user()))
                                            <form method="POST" action="{{ route('activities.join', $activity) }}" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-500 to-teal-600 text-white text-sm font-medium rounded-lg hover:from-green-600 hover:to-teal-700 transition-all duration-200">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                                    </svg>
                                                    Unirme
                                                </button>
                                            </form>
                                        @else
                                            <span class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 text-sm font-medium rounded-lg cursor-not-allowed">
                                                Ya participas
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Load More or Pagination -->
                        <div class="mt-8 text-center">
                            <p class="text-gray-600 dark:text-gray-400 text-sm">
                                ¿No encuentras lo que buscas?
                                <a href="{{ route('activities.index') }}" class="text-purple-600 hover:text-purple-800 font-medium">Explora todas las actividades</a>
                            </p>
                        </div>

                    @else
                        <!-- No Recommendations -->
                        <div class="text-center py-16">
                            <div class="w-24 h-24 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">No hay recomendaciones disponibles</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                                Esto puede deberse a que aún no has configurado tus habilidades o no hay actividades que coincidan con tu perfil actual.
                            </p>

                            <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                                <a href="{{ route('skills.setup') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white font-medium rounded-lg hover:from-purple-600 hover:to-pink-700 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Configurar Habilidades
                                </a>

                                <a href="{{ route('activities.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Explorar Actividades
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
