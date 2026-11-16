<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- User Ranking Display -->
            @if(!auth()->user()->isAdmin() && $userRanking)
            <x-content-card class="mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">#{{ $userRanking->rank_position ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">Posición</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $userRanking->total_points }}</div>
                            <div class="text-sm text-gray-500">Puntos</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $userRanking->rank }}</div>
                            <div class="text-sm text-gray-500">Rango</div>
                        </div>
                    </div>
                    <x-action-button variant="info" href="{{ route('rankings.index') }}">
                        Ver Rankings Completos →
                    </x-action-button>
                </div>
            </x-content-card>
            @endif

            <x-page-header title="Actividades de Voluntariado" subtitle="Descubre oportunidades para hacer la diferencia">
                <x-slot name="actions">
                    <div class="flex space-x-3">
                        <!-- View Toggle -->
                        <div class="flex bg-gray-100 rounded-lg p-1">
                            <a href="{{ route('activities.index', array_merge(request()->query(), ['view' => 'grid'])) }}" 
                               class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ ($view ?? 'grid') === 'grid' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                <svg class="w-4 h-4 mr-1 inline" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                                Tarjetas
                            </a>
                            <a href="{{ route('activities.index', array_merge(request()->query(), ['view' => 'calendar'])) }}" 
                               class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ ($view ?? 'grid') === 'calendar' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                <svg class="w-4 h-4 mr-1 inline" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                Calendario
                            </a>
                        </div>
                        
                        @if(auth()->user()->isAdmin())
                            <x-action-button variant="primary" href="{{ route('activities.create') }}">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                </svg>
                                Nueva Actividad
                            </x-action-button>
                        @endif
                        <x-action-button variant="secondary" href="{{ route('activities.history') }}">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            Historial
                        </x-action-button>
                    </div>
                </x-slot>
            </x-page-header>

            <!-- Filters and Search -->
            <x-content-card class="mb-6">
                <form id="activity-filters" method="GET" action="{{ route('activities.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Nombre, descripción, ubicación..." 
                                       class="filter-input w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Todas las categorías</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if(auth()->user()->isAdmin())
                        <!-- Status Filter (Admin only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Todos los estados</option>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Sort Options -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ordenar por</label>
                            <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Más recientes</option>
                                <option value="start_date" {{ request('sort') == 'start_date' ? 'selected' : '' }}>Fecha de inicio</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="flex space-x-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                </svg>
                                Filtrar
                            </button>
                            <a href="{{ route('activities.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                Limpiar
                            </a>
                        </div>
                        <div class="text-sm text-gray-600">
                            @if(method_exists($activities, 'total'))
                                Mostrando {{ $activities->count() }} de {{ $activities->total() }} actividades
                            @else
                                {{ $activities->count() }} actividades
                            @endif
                        </div>
                    </div>
                </form>
            </x-content-card>

            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
            @endif

            <!-- Recommendations Section (for non-admin users) -->
            @if(!auth()->user()->isAdmin() && $recommendations->count() > 0)
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">🎯 Recomendadas para ti</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($recommendations as $activity)
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border-2 border-blue-200">
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="text-lg font-semibold text-gray-900">{{ is_object($activity) ? ($activity->title ?? $activity->name) : ($activity['title'] ?? $activity['name'] ?? 'Sin nombre') }}</h3>
                                <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-medium">Recomendada</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit((is_object($activity) ? $activity->description : $activity['description'] ?? null) ?? 'Sin descripción', 80) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-blue-600 font-medium">
                                    @if(is_object($activity))
                                        {{ $activity->getCategoryEmoji() }} {{ ucfirst(str_replace('_', ' ', $activity->category)) }}
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $activity['category'] ?? 'general')) }}
                                    @endif
                                </span>
                                <x-action-button variant="primary" size="sm" href="{{ route('activities.show', is_object($activity) ? $activity->id : ($activity['id'] ?? 1)) }}">
                                    Ver más
                                </x-action-button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(($view ?? 'grid') === 'calendar')
            <!-- Calendar View -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div id="calendar"></div>
                
                <!-- Leyenda de Colores -->
                <div class="mt-6 p-5 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border-2 border-gray-200 shadow-sm">
                    <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Leyenda de Estados de Actividades
                    </h4>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        <div class="flex items-center space-x-3 p-2 bg-white rounded-lg hover:shadow-md transition-shadow">
                            <div class="w-5 h-5 rounded-md bg-green-500 shadow-md flex-shrink-0"></div>
                            <div>
                                <span class="text-sm font-semibold text-gray-800 block">🟢 Abierta</span>
                                <span class="text-xs text-gray-500">Aceptando participantes</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 p-2 bg-white rounded-lg hover:shadow-md transition-shadow">
                            <div class="w-5 h-5 rounded-md bg-yellow-500 shadow-md flex-shrink-0"></div>
                            <div>
                                <span class="text-sm font-semibold text-gray-800 block">🟡 Próxima</span>
                                <span class="text-xs text-gray-500">Por iniciar</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 p-2 bg-white rounded-lg hover:shadow-md transition-shadow">
                            <div class="w-5 h-5 rounded-md bg-blue-500 shadow-md flex-shrink-0"></div>
                            <div>
                                <span class="text-sm font-semibold text-gray-800 block">🔵 En Progreso</span>
                                <span class="text-xs text-gray-500">Ejecutándose</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 p-2 bg-white rounded-lg hover:shadow-md transition-shadow">
                            <div class="w-5 h-5 rounded-md bg-green-400 shadow-md flex-shrink-0"></div>
                            <div>
                                <span class="text-sm font-semibold text-gray-800 block">✅ Completada</span>
                                <span class="text-xs text-gray-500">Finalizada</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 p-2 bg-white rounded-lg hover:shadow-md transition-shadow">
                            <div class="w-5 h-5 rounded-md bg-red-500 shadow-md flex-shrink-0"></div>
                            <div>
                                <span class="text-sm font-semibold text-gray-800 block">🔴 Cancelada</span>
                                <span class="text-xs text-gray-500">No se realizó</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @elseif($activities->count() > 0)
            <!-- Grid View -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($activities as $activity)
                <div class="activity-card bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200">
                    <!-- Activity Header -->
                    <div class="bg-gradient-to-r from-{{ $activity->category == 'educacion' ? 'blue' : ($activity->category == 'medio_ambiente' ? 'green' : ($activity->category == 'salud' ? 'red' : ($activity->category == 'social' ? 'purple' : 'indigo'))) }}-500 to-{{ $activity->category == 'educacion' ? 'blue' : ($activity->category == 'medio_ambiente' ? 'green' : ($activity->category == 'salud' ? 'red' : ($activity->category == 'social' ? 'purple' : 'indigo'))) }}-600 p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl">{{ $activity->getCategoryEmoji() }}</span>
                                <div>
                                    <h3 class="font-semibold text-lg">{{ $activity->title ?? $activity->name }}</h3>
                                    <p class="text-sm opacity-90">{{ ucfirst(str_replace('_', ' ', $activity->category)) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs opacity-75">Puntos estimados</div>
                                <div class="text-lg font-bold">{{ $activity->getEstimatedPoints() }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Description -->
                            <p class="text-gray-600 text-sm leading-relaxed">{{ Str::limit($activity->description ?? 'Sin descripción disponible', 120) }}</p>
                            
                            <!-- Activity Details -->
                            <div class="space-y-3">
                                <!-- Status -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $activity->status == 'active' ? 'bg-green-100 text-green-800' : ($activity->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">{{ ucfirst($activity->status) }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Dificultad: <span class="font-medium capitalize">{{ $activity->getDifficultyLevel() }}</span>
                                    </div>
                                </div>
                                
                                <!-- Location -->
                                @if($activity->location)
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $activity->location }}
                                </div>
                                @endif
                                
                                <!-- Date and Time -->
                                @if($activity->start_date)
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <div>{{ $activity->start_date->format('d/m/Y H:i') }}</div>
                                        @if($activity->end_date)
                                            <div class="text-xs text-gray-500">hasta {{ $activity->end_date->format('d/m/Y H:i') }}</div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                <!-- Participants -->
                                @if($activity->max_participants)
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                    </svg>
                                    {{ $activity->current_participants }}/{{ $activity->max_participants }} participantes
                                    @if($activity->isFull())
                                        <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">Completo</span>
                                    @endif
                                </div>
                                @endif

                                <!-- Skills -->
                                @if($activity->skills->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($activity->skills->take(3) as $skill)
                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs">{{ $skill->name }}</span>
                                    @endforeach
                                    @if($activity->skills->count() > 3)
                                        <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded-full text-xs">+{{ $activity->skills->count() - 3 }} más</span>
                                    @endif
                                </div>
                                @endif
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                                <span class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                                <div class="flex space-x-2">
                                    <x-action-button variant="info" size="sm" href="{{ route('activities.show', $activity) }}">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Ver
                                    </x-action-button>
                                    @if(auth()->user()->isAdmin())
                                        @if(!$activity->isCompleted())
                                            <x-action-button variant="success" size="sm" href="{{ route('activities.edit', $activity) }}">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                </svg>
                                                Editar
                                            </x-action-button>
                                        @endif
                                    @else
                                        @php
                                            $userJoined = $activity->users->contains(auth()->id());
                                            $participationStatus = $activity->getUserParticipationStatus();
                                        @endphp
                                        @if($userJoined)
                                            @if($participationStatus === 'joined')
                                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Inscrito
                                                </span>
                                            @elseif($participationStatus === 'pending')
                                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-medium flex items-center">
                                                    <svg class="w-3 h-3 mr-1 animate-spin" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Pendiente
                                                </span>
                                            @elseif($participationStatus === 'completed')
                                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Completada
                                                </span>
                                            @endif
                                        @elseif(in_array($activity->status, ['completed', 'completada']))
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">Completada</span>
                                        @elseif(!in_array($activity->status, ['abierta', 'active', 'proxima']))
                                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-medium">No disponible</span>
                                        @elseif($activity->isFull())
                                            <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-xs font-medium">Completo</span>
                                        @else
                                            <div class="flex flex-col items-end space-y-1">
                                                <div class="text-xs text-green-600 font-medium">+{{ $activity->getEstimatedPoints() }} puntos</div>
                                                <form action="{{ route('activities.join', $activity) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition duration-200">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Unirse
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if(($view ?? 'grid') !== 'calendar' && method_exists($activities, 'links'))
            <!-- Pagination -->
            <div class="mt-8">
                {{ $activities->links() }}
            </div>
            @endif
            @else
            <x-content-card>
                <div class="text-center py-16">
                    <div class="mb-6">
                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No se encontraron actividades</h3>
                    <p class="text-gray-500 mb-6">No hay actividades que coincidan con tus criterios de búsqueda.</p>
                    @if(request()->hasAny(['search', 'category', 'status']))
                        <a href="{{ route('activities.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Ver todas las actividades
                        </a>
                    @endif
                </div>
            </x-content-card>
            @endif
        </div>
    </div>

    @if(($view ?? 'grid') === 'calendar')
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        @keyframes slideUp {
            from { transform: translateY(30px) scale(0.95); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        .animate-fadeIn { animation: fadeIn 0.3s ease-out; }
        .animate-fadeOut { animation: fadeOut 0.2s ease-out; }
        .animate-slideUp { animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .animate-pulse { animation: pulse 2s infinite; }
        
        #calendar {
            font-family: inherit;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(118, 75, 162, 0.15) 50%, rgba(240, 147, 251, 0.15) 100%);
            padding: 2rem;
            border-radius: 1.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        
        .fc {
            background: white;
            border-radius: 1.25rem;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        
        .fc .fc-toolbar-title {
            font-size: 1.875rem !important;
            font-weight: 800;
            color: #1f2937;
        }
        
        .fc .fc-button-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            border: none;
            border-radius: 0.75rem;
            padding: 0.625rem 1.25rem;
            font-weight: 700;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .fc .fc-button-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        .fc .fc-button-primary:hover::before {
            width: 300px;
            height: 300px;
        }
        .fc .fc-button-primary:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.7);
        }
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background: linear-gradient(135deg, #f093fb 0%, #764ba2 50%, #667eea 100%);
            box-shadow: 0 6px 20px rgba(240, 147, 251, 0.6);
            transform: scale(0.98);
        }
        
        .fc-daygrid-day {
            transition: all 0.3s ease;
        }
        .fc-daygrid-day:hover {
            background: rgba(102, 126, 234, 0.05);
        }
        
        .fc-daygrid-day-number {
            font-weight: 600;
            color: #1f2937;
            padding: 0.5rem;
        }
        
        .fc-day-today {
            background: rgba(102, 126, 234, 0.08) !important;
        }
        
        .fc-day-today .fc-daygrid-day-number {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        }
        
        .fc-event {
            border-radius: 0.75rem;
            padding: 6px 10px;
            font-size: 0.875rem;
            font-weight: 700;
            border: none !important;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .fc-event::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        .fc-event:hover::before {
            left: 100%;
        }
        .fc-event:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            z-index: 10;
            filter: brightness(1.1);
        }
        
        .fc-daygrid-event {
            white-space: normal;
            margin: 2px 0;
        }
        
        .fc-h-event {
            border-left-width: 4px !important;
        }
        
        .fc .fc-more-link {
            color: #667eea;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .fc .fc-more-link:hover {
            color: #764ba2;
            transform: scale(1.1);
        }
    </style>
    
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Lista'
            },
            height: 'auto',
            events: '{{ route('activities.calendar-data') }}',
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                showActivityModal(info.event);
            },
            eventDidMount: function(info) {
                info.el.style.cursor = 'pointer';
                info.el.setAttribute('title', info.event.title);
            },
            eventClassNames: function(arg) {
                return ['hover:opacity-80', 'transition-opacity', 'duration-200'];
            },
            dayMaxEvents: 3,
            moreLinkText: 'más',
            noEventsText: 'No hay actividades para mostrar'
        });
        calendar.render();
        
        function showActivityModal(event) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gradient-to-br from-black/60 via-purple-900/30 to-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fadeIn';
            modal.innerHTML = `
                <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden transform transition-all animate-slideUp">
                    <div class="relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br ${event.backgroundColor || 'from-purple-600 via-blue-600 to-indigo-700'} opacity-90"></div>
                        <div class="absolute inset-0" style="background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%); background-size: 200% 200%; animation: shimmer 3s infinite;"></div>
                        <div class="relative p-8">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <span class="text-5xl animate-pulse">${event.extendedProps.emoji || '📅'}</span>
                                        <span class="bg-white/30 backdrop-blur-md text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg border border-white/20">
                                            ${event.extendedProps.category || 'General'}
                                        </span>
                                    </div>
                                    <h3 class="text-3xl font-black text-white drop-shadow-lg mb-2">${event.title}</h3>
                                    ${event.extendedProps.status ? `
                                        <span class="inline-flex items-center bg-green-400/90 text-green-900 px-3 py-1 rounded-full text-xs font-bold">
                                            <span class="w-2 h-2 bg-green-600 rounded-full mr-2 animate-pulse"></span>
                                            ${event.extendedProps.status}
                                        </span>
                                    ` : ''}
                                </div>
                                <button class="close-modal text-white hover:bg-white/20 hover:rotate-90 rounded-full p-2 transition-all duration-300 backdrop-blur-sm">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-8 space-y-5 overflow-y-auto max-h-[50vh]">
                        ${event.extendedProps.description ? `
                            <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-5 rounded-2xl border-l-4 border-blue-500">
                                <h4 class="text-sm font-bold text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    Descripción
                                </h4>
                                <p class="text-gray-700 leading-relaxed font-medium">${event.extendedProps.description}</p>
                            </div>
                        ` : ''}
                        <div class="grid grid-cols-1 gap-4">
                            <div class="group flex items-start space-x-4 p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200 hover:shadow-lg hover:scale-105 transition-all duration-300">
                                <div class="p-3 bg-purple-500 rounded-xl shadow-md group-hover:rotate-12 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-purple-700 uppercase tracking-wide mb-1">Fecha y Hora</p>
                                    <p class="text-sm text-gray-800 font-bold">${event.start.toLocaleString('es-ES', {dateStyle: 'full', timeStyle: 'short'})}</p>
                                    ${event.end ? `<p class="text-xs text-purple-600 mt-1 font-medium">→ ${event.end.toLocaleString('es-ES', {dateStyle: 'medium', timeStyle: 'short'})}</p>` : ''}
                                </div>
                            </div>
                            ${event.extendedProps.location ? `
                                <div class="group flex items-start space-x-4 p-4 bg-gradient-to-br from-orange-50 to-red-50 rounded-xl border border-orange-200 hover:shadow-lg hover:scale-105 transition-all duration-300">
                                    <div class="p-3 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl shadow-md group-hover:rotate-12 transition-transform duration-300">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-bold text-orange-700 uppercase tracking-wide mb-1">Ubicación</p>
                                        <p class="text-sm text-gray-800 font-bold">${event.extendedProps.location}</p>
                                    </div>
                                </div>
                            ` : ''}
                            ${event.extendedProps.participants ? `
                                <div class="group flex items-start space-x-4 p-4 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl border border-blue-200 hover:shadow-lg hover:scale-105 transition-all duration-300">
                                    <div class="p-3 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl shadow-md group-hover:rotate-12 transition-transform duration-300">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-bold text-blue-700 uppercase tracking-wide mb-1">Participantes</p>
                                        <p class="text-sm text-gray-800 font-bold">${event.extendedProps.participants}</p>
                                    </div>
                                </div>
                            ` : ''}
                            ${event.extendedProps.points ? `
                                <div class="group flex items-start space-x-4 p-4 bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl border border-yellow-300 hover:shadow-lg hover:scale-105 transition-all duration-300">
                                    <div class="p-3 bg-gradient-to-br from-yellow-400 to-amber-500 rounded-xl shadow-md group-hover:rotate-12 transition-transform duration-300 animate-pulse">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-bold text-yellow-700 uppercase tracking-wide mb-1">Puntos Estimados</p>
                                        <p class="text-2xl text-transparent bg-clip-text bg-gradient-to-r from-yellow-600 to-amber-600 font-black">${event.extendedProps.points} pts</p>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                        <div class="flex justify-between items-center pt-6 border-t-2 border-gray-200">
                            <button class="close-modal px-5 py-2.5 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-xl font-semibold transition-all duration-300">
                                ← Cerrar
                            </button>
                            <a href="/activities/${event.id}" class="group relative px-6 py-3 bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-600 hover:from-purple-700 hover:via-blue-700 hover:to-indigo-700 text-white rounded-xl font-bold transition-all duration-300 shadow-lg hover:shadow-2xl hover:scale-105 overflow-hidden">
                                <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity duration-300"></span>
                                <span class="relative flex items-center">
                                    Ver Detalles Completos
                                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            modal.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    modal.classList.add('animate-fadeOut');
                    setTimeout(() => modal.remove(), 200);
                });
            });
            
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('animate-fadeOut');
                    setTimeout(() => modal.remove(), 200);
                }
            });
        }
    });
    </script>
    @endif
</x-app-layout>