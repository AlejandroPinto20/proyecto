<x-app-layout>
    <x-page-header title="Calendario de Actividades" subtitle="Gestiona y participa en actividades de voluntariado" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-content-card>
                <!-- Tabs Navigation -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <button id="activities-tab" class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600 active">
                            Actividades
                        </button>
                        <button id="history-tab" class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Historial de Actividades
                        </button>
                        <button id="calendar-tab" class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Calendario
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">

                    <!-- Activities Tab Content -->
                    <div id="activities-content" class="tab-content">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Todas las Actividades</h3>

                        </div>

                        @if($skills->count() > 0)
                            <!-- User Skills Section -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-lg p-6 mb-6 border border-blue-200 dark:border-gray-500">
                                <h4 class="text-xl font-semibold text-blue-900 dark:text-blue-100 mb-4 flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Mis Habilidades (para Cadenas de Markov y Recomendaciones)
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($skills as $skill)
                                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-600">
                                            <div class="flex items-center justify-between mb-2">
                                                <h5 class="font-medium text-gray-900 dark:text-gray-100">{{ $skill->name }}</h5>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">Nivel {{ $skill->pivot->level ?? 1 }}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                <div class="bg-gradient-to-r from-unifranz-orange to-unifranz-orange-dark h-2 rounded-full" style="width: {{ (($skill->pivot->level ?? 1) / 5) * 100 }}%"></div>
                                            </div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $skill->description ?? 'Habilidad para desarrollo personal' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-sm text-blue-700 dark:text-blue-300 mt-4">
                                    💡 Estas habilidades se utilizan para generar recomendaciones personalizadas mediante cadenas de Markov, analizando tus patrones de participación en actividades.
                                </p>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($activities->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($activities as $activity)
                                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                                        <div class="p-6">
                                            <div class="space-y-4">
                                                <div>
                                                    <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ $activity->name }}</h4>
                                                    <p class="text-gray-600">{{ $activity->description ?? 'Sin descripción' }}</p>
                                                </div>
                                                
                                                <div class="space-y-2">
                                                    @if($activity->category)
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                                        </svg>
                                                        <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $activity->category)) }}</span>
                                                    </div>
                                                    @endif
                                                    
                                                    <div class="flex items-center text-sm">
                                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $activity->status == 'active' ? 'bg-green-100 text-green-800' : ($activity->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">{{ ucfirst($activity->status) }}</span>
                                                    </div>
                                                    
                                                    @if($activity->location)
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <svg class="w-4 h-4 mr-2 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ $activity->location }}
                                                    </div>
                                                    @endif
                                                    
                                                    @if($activity->start_date)
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <svg class="w-4 h-4 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ $activity->start_date->format('d/m/Y H:i') }}
                                                    </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                                                    <span class="text-sm text-gray-500">Creada: {{ $activity->created_at->format('d M Y') }}</span>
                                                    <div class="flex space-x-2">
                                                        <x-action-button variant="info" size="sm" href="{{ route('activities.show', $activity) }}">
                                                            Ver Detalles
                                                        </x-action-button>
                                                        @if(auth()->user()->isAdmin())
                                                            @if(!$activity->isCompleted())
                                                                <x-action-button variant="success" size="sm" href="{{ route('activities.edit', $activity) }}">
                                                                    Editar
                                                                </x-action-button>
                                                                <form action="{{ route('activities.destroy', $activity) }}" method="POST" class="inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <x-action-button variant="danger" size="sm" type="submit" onclick="return confirm('¿Estás seguro?')">
                                                                        Eliminar
                                                                    </x-action-button>
                                                                </form>
                                                            @endif
                                                        @else
                                                            @php
                                                                $userJoined = $activity->users->contains(auth()->id());
                                                            @endphp
                                                            @if($userJoined)
                                                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">✓ Ya inscrito</span>
                                                            @elseif($activity->status !== 'active')
                                                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-medium">✗ No disponible</span>
                                                            @else
                                                                <x-action-button variant="primary" size="sm" href="{{ route('calendar.show', $activity) }}">
                                                                    Unirse
                                                                </x-action-button>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                @if(auth()->user()->isAdmin()) <a href="{{ route('activities.create') }}" class="text-unifranz-orange hover:text-unifranz-orange-dark">Crear tu primera actividad</a> @endif
                            </div>
                        @endif
                    </div>

                    <!-- History Tab Content -->
                    <div id="history-content" class="tab-content hidden">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ auth()->user()->isAdmin() ? 'Historial de Actividades' : 'Mi Historial de Actividades' }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 mt-2">Actividades completadas y canceladas</p>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($historyActivities->count() > 0)
                            <!-- Status Filter Tabs -->
                            <div class="mb-6">
                                <div class="border-b border-gray-200 dark:border-gray-700">
                                    <nav class="-mb-px flex space-x-8">
                                        <button id="all-history-tab" class="history-filter-tab active whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                                            Todas ({{ $historyActivities->count() }})
                                        </button>
                                        <button id="completed-history-tab" class="history-filter-tab whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                            Completadas ({{ $historyActivities->where('status', 'completed')->count() }})
                                        </button>
                                        <button id="cancelled-history-tab" class="history-filter-tab whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                            Canceladas ({{ $historyActivities->where('status', 'cancelled')->count() }})
                                        </button>
                                    </nav>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($historyActivities as $activity)
                                    <div class="activity-card bg-white dark:bg-gray-700 rounded-lg shadow-md p-6 border-l-4 {{ $activity->status == 'completed' ? 'border-l-green-500' : 'border-l-red-500' }}" data-status="{{ $activity->status }}">
                                        <div class="flex items-start justify-between mb-3">
                                            <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $activity->name }}</h4>
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $activity->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $activity->status == 'completed' ? 'Completada' : 'Cancelada' }}
                                            </span>
                                        </div>

                                        <p class="text-gray-600 dark:text-gray-400 mb-3">{{ $activity->description ?? 'Sin descripción' }}</p>

                                        @if($activity->category)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                                </svg>
                                                Categoría: {{ ucfirst(str_replace('_', ' ', $activity->category)) }}
                                            </p>
                                        @endif

                                        @if($activity->location)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Ubicación: {{ $activity->location }}
                                            </p>
                                        @endif

                                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-3">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                            @if($activity->start_date)
                                                {{ $activity->start_date->format('d M Y') }}
                                                @if($activity->end_date)
                                                    - {{ $activity->end_date->format('d M Y') }}
                                                @endif
                                            @else
                                                Sin fecha definida
                                            @endif
                                        </div>

                                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                            </svg>
                                            {{ $activity->current_participants }} participante{{ $activity->current_participants != 1 ? 's' : '' }}
                                            @if($activity->max_participants)
                                                / {{ $activity->max_participants }} máximo
                                            @endif
                                        </div>

                                        <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-600">
                                            <small class="text-gray-500 dark:text-gray-400">
                                                @if(auth()->user()->isAdmin())
                                                    Creada por: {{ $activity->user->name }}
                                                @else
                                                    Creada: {{ $activity->created_at->format('d M Y') }}
                                                @endif
                                            </small>
                                            <x-action-button href="{{ route('activities.show', $activity) }}" variant="info" size="sm">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                Ver Detalles
                                            </x-action-button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="max-w-md mx-auto">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h4 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No hay actividades en el historial</h4>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Las actividades completadas y canceladas aparecerán aquí.</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Calendar Tab Content -->
                    <div id="calendar-content" class="tab-content hidden">
                        <div class="bg-gradient-to-br from-white via-orange-50 to-red-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900 overflow-hidden shadow-2xl sm:rounded-xl border border-gray-200 dark:border-gray-700">
                            <div class="p-6 lg:p-8 bg-gradient-to-r from-orange-500 via-red-500 to-pink-500 border-b border-white/20">
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <h4 class="text-3xl font-bold text-gray-900 flex items-center">
                                            <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Actividades Programadas
                                        </h4>
                                        <p class="mt-2 text-gray-700 text-lg">
                                            Visualiza y participa en las actividades de voluntariado programadas
                                        </p>
                                    </div>
                                    <div class="flex space-x-3">
                                        @if(auth()->user()->isAdmin())
                                            <button id="createActivityBtn" class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-xl transition-all duration-300 backdrop-blur-sm border border-white/30 shadow-lg hover:shadow-xl transform hover:scale-105">
                                                <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                Crear Actividad
                                            </button>
                                        @endif
                                        <button id="todayBtn" class="px-4 py-3 bg-white/20 hover:bg-white/30 text-white rounded-xl transition-all duration-300 backdrop-blur-sm border border-white/30 shadow-lg hover:shadow-xl">
                                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Hoy
                                        </button>
                                        <button id="monthBtn" class="px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-all duration-300 backdrop-blur-sm border border-white/20 shadow-lg">
                                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"></path>
                                            </svg>
                                            Mes
                                        </button>
                                        <button id="weekBtn" class="px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-all duration-300 backdrop-blur-sm border border-white/20 shadow-lg">
                                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Semana
                                        </button>
                                        <button id="dayBtn" class="px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-all duration-300 backdrop-blur-sm border border-white/20 shadow-lg">
                                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Día
                                        </button>
                                    </div>
                                </div>

                                <!-- Calendar Legend -->
                                <div class="mb-6 flex flex-wrap gap-6 p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                                    <div class="flex items-center">
                                        <div class="w-5 h-5 bg-gradient-to-r from-green-400 to-green-600 rounded-lg mr-3 shadow-lg"></div>
                                        <span class="text-gray-900 font-medium">Activa</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-5 h-5 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-lg mr-3 shadow-lg"></div>
                                        <span class="text-gray-900 font-medium">Pendiente</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-5 h-5 bg-gradient-to-r from-gray-400 to-gray-600 rounded-lg mr-3 shadow-lg"></div>
                                        <span class="text-gray-900 font-medium">Completada</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-5 h-5 bg-gradient-to-r from-red-400 to-red-600 rounded-lg mr-3 shadow-lg"></div>
                                        <span class="text-gray-900 font-medium">Cancelada</span>
                                    </div>
                                </div>

                                <!-- Calendar Container -->
                                <div id="calendar" class="bg-white dark:bg-gray-50 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-600 overflow-hidden" style="min-height: 600px;"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </x-content-card>
        </div>
    </div>

    <!-- FullCalendar Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <!-- Leaflet Map Scripts and Styles -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });

                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Add active class to clicked tab
                    this.classList.add('active', 'border-blue-500', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-gray-500');

                    // Show corresponding content
                    const tabId = this.id.replace('-tab', '');
                    const contentId = `${tabId}-content`;
                    const content = document.getElementById(contentId);
                    if (content) {
                        content.classList.remove('hidden');
                        
                        // If switching to calendar tab, ensure calendar renders properly
                        if (tabId === 'calendar' && calendar) {
                            setTimeout(() => {
                                calendar.updateSize();
                                calendar.render();
                            }, 100);
                        }
                    }
                });
            });

            // History filter tabs functionality
            const historyFilterTabs = document.querySelectorAll('.history-filter-tab');
            const activityCards = document.querySelectorAll('.activity-card');

            historyFilterTabs.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all history filter tabs
                    historyFilterTabs.forEach(btn => {
                        btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });

                    // Add active class to clicked tab
                    this.classList.add('active', 'border-blue-500', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-gray-500');

                    // Filter activities
                    const filterStatus = this.id.replace('-history-tab', '');

                    activityCards.forEach(card => {
                        if (filterStatus === 'all' || card.dataset.status === filterStatus) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día'
                },
                events: {
                    url: '{{ route("calendar.activities") }}',
                    method: 'GET',
                    extraParams: {
                        exclude_completed: true
                    },
                    failure: function() {
                        console.error('Error loading calendar events');
                    }
                },
                eventClick: function(info) {
                    showActivityDetails(info.event.id);
                },
                eventDidMount: function(info) {
                    const status = info.event.extendedProps.status;
                    switch(status) {
                        case 'active':
                            info.el.style.backgroundColor = '#10B981';
                            info.el.style.borderColor = '#059669';
                            break;
                        case 'pending':
                            info.el.style.backgroundColor = '#F59E0B';
                            info.el.style.borderColor = '#D97706';
                            break;
                        case 'completed':
                            info.el.style.backgroundColor = '#6B7280';
                            info.el.style.borderColor = '#4B5563';
                            break;
                        case 'cancelled':
                            info.el.style.backgroundColor = '#EF4444';
                            info.el.style.borderColor = '#DC2626';
                            break;
                    }
                    info.el.style.color = 'white';
                },
                height: 'auto',
                dayMaxEvents: true,
                moreLinkClick: 'popover',
                dayHeaderFormat: { weekday: 'long' }
            });

            // Ensure calendar renders properly on page load
            setTimeout(function() {
                calendar.render();
                // Force refresh events after render
                calendar.refetchEvents();
            }, 100);

            // View buttons
            if (document.getElementById('todayBtn')) {
                document.getElementById('todayBtn').addEventListener('click', () => calendar.today());
            }
            if (document.getElementById('monthBtn')) {
                document.getElementById('monthBtn').addEventListener('click', () => calendar.changeView('dayGridMonth'));
            }
            if (document.getElementById('weekBtn')) {
                document.getElementById('weekBtn').addEventListener('click', () => calendar.changeView('timeGridWeek'));
            }
            if (document.getElementById('dayBtn')) {
                document.getElementById('dayBtn').addEventListener('click', () => calendar.changeView('timeGridDay'));
            }

            // Create Activity button
            if (document.getElementById('createActivityBtn')) {
                document.getElementById('createActivityBtn').addEventListener('click', () => {
                    window.location.href = '{{ route("activities.create") }}';
                });
            }

            function showActivityDetails(activityId) {
                fetch(`{{ url('/calendar/activities') }}/${activityId}`)
                    .then(response => response.json())
                    .then(activity => {
                        // Create a beautiful modal content
                        modalTitle.textContent = activity.name;

                        const statusColor = {
                            'active': 'bg-green-100 text-green-800 border-green-200',
                            'pending': 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'completed': 'bg-gray-100 text-gray-800 border-gray-200',
                            'cancelled': 'bg-red-100 text-red-800 border-red-200'
                        };

                        const statusText = {
                            'active': 'Activa',
                            'pending': 'Pendiente',
                            'completed': 'Completada',
                            'cancelled': 'Cancelada'
                        };

                        modalContent.innerHTML = `
                            <div class="space-y-6">
                                <!-- Header with status badge -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-r from-unifranz-orange to-unifranz-orange-dark rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">${activity.name}</h3>
                                            <p class="text-sm text-gray-600">Organizado por ${activity.organizer}</p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium border ${statusColor[activity.status] || 'bg-gray-100 text-gray-800 border-gray-200'}">
                                        ${statusText[activity.status] || activity.status}
                                    </span>
                                </div>

                                <!-- Description -->
                                ${activity.description ? `
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                                        <h4 class="text-sm font-semibold text-blue-900 mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                            </svg>
                                            Descripción
                                        </h4>
                                        <p class="text-blue-800 text-sm">${activity.description}</p>
                                    </div>
                                ` : ''}

                                <!-- Details Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Schedule Info -->
                                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-4 border border-green-200">
                                        <h4 class="text-sm font-semibold text-green-900 mb-3 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Información del Evento
                                        </h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex items-center text-green-800">
                                                <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-medium">Inicio:</span> ${new Date(activity.start_date).toLocaleString('es-ES')}
                                            </div>
                                            <div class="flex items-center text-green-800">
                                                <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-medium">Fin:</span> ${new Date(activity.end_date).toLocaleString('es-ES')}
                                            </div>
                                            ${activity.location ? `
                                                <div class="flex items-center text-green-800">
                                                    <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="font-medium">Ubicación:</span> ${activity.location}
                                                </div>
                                            ` : ''}
                                        </div>
                                    </div>

                                    <!-- Participants Info -->
                                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-4 border border-purple-200">
                                        <h4 class="text-sm font-semibold text-purple-900 mb-3 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                            </svg>
                                            Participantes
                                        </h4>
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between text-purple-800">
                                                <span class="text-sm font-medium">Registrados:</span>
                                                <span class="text-lg font-bold">${activity.current_participants}</span>
                                            </div>
                                            ${activity.max_participants ? `
                                                <div class="flex items-center justify-between text-purple-800">
                                                    <span class="text-sm font-medium">Máximo:</span>
                                                    <span class="text-lg font-bold">${activity.max_participants}</span>
                                                </div>
                                                <div class="w-full bg-purple-200 rounded-full h-2 mt-2">
                                                    <div class="bg-purple-600 h-2 rounded-full" style="width: ${(activity.current_participants / activity.max_participants) * 100}%"></div>
                                                </div>
                                            ` : `
                                                <div class="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded">
                                                    Sin límite de participantes
                                                </div>
                                            `}
                                        </div>
                                    </div>
                                </div>

                                <!-- Category Badge -->
                                ${activity.category ? `
                                    <div class="flex items-center justify-center">
                                        <span class="px-4 py-2 bg-gradient-to-r from-unifranz-orange to-unifranz-orange-dark text-white text-sm font-medium rounded-full shadow-lg">
                                            ${activity.category.charAt(0).toUpperCase() + activity.category.slice(1).replace('_', ' ')}
                                        </span>
                                    </div>
                                ` : ''}
                            </div>
                        `;

                        // Clear existing action buttons
                        actionButtons.innerHTML = '';

                        // Create action buttons based on user participation status
                        const isParticipating = activity.participants && activity.participants.includes({{ auth()->id() }});
                        const canJoin = !activity.is_full && activity.status === 'active';
                        const canLeave = isParticipating && activity.status !== 'completed' && activity.status !== 'cancelled';

                        if (canJoin && !isParticipating) {
                            const joinButton = document.createElement('button');
                            joinButton.className = 'join-btn px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg font-medium flex items-center';
                            joinButton.innerHTML = `
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Unirse a la Actividad
                            `;
                            joinButton.addEventListener('click', () => joinActivity(activityId));
                            actionButtons.appendChild(joinButton);
                        } else if (canLeave && isParticipating) {
                            const leaveButton = document.createElement('button');
                            leaveButton.className = 'leave-btn px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg font-medium flex items-center';
                            leaveButton.innerHTML = `
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Abandonar Actividad
                            `;
                            leaveButton.addEventListener('click', () => leaveActivity(activityId));
                            actionButtons.appendChild(leaveButton);
                        } else {
                            const disabledButton = document.createElement('div');
                            disabledButton.className = 'px-6 py-3 bg-gradient-to-r from-gray-400 to-gray-500 text-white rounded-lg font-medium shadow-lg opacity-50 cursor-not-allowed flex items-center';
                            disabledButton.innerHTML = `
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                                ${activity.status === 'completed' ? 'Actividad Completada' : activity.status === 'cancelled' ? 'Actividad Cancelada' : activity.is_full ? 'Actividad Llena' : 'No disponible'}
                            `;
                            actionButtons.appendChild(disabledButton);
                        }

                        modal.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error loading activity details:', error);
                        alert('Error al cargar detalles de la actividad');
                    });
            }
        });
    </script>
</x-app-layout>