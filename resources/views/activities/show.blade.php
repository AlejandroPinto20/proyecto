<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header with gradient background -->
            <div class="bg-gradient-to-r from-{{ $activity->category == 'educacion' ? 'blue' : ($activity->category == 'medio_ambiente' ? 'green' : ($activity->category == 'salud' ? 'red' : ($activity->category == 'social' ? 'purple' : 'indigo'))) }}-500 to-{{ $activity->category == 'educacion' ? 'blue' : ($activity->category == 'medio_ambiente' ? 'green' : ($activity->category == 'salud' ? 'red' : ($activity->category == 'social' ? 'purple' : 'indigo'))) }}-600 rounded-t-xl p-8 text-white">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4">
                        <div class="text-4xl">{{ $activity->getCategoryEmoji() }}</div>
                        <div>
                            <h1 class="text-3xl font-bold mb-2">{{ $activity->name }}</h1>
                            <div class="flex items-center space-x-4 text-sm opacity-90">
                                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full">{{ ucfirst(str_replace('_', ' ', $activity->category)) }}</span>
                                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full">{{ $activity->getEstimatedPoints() }} puntos</span>
                                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full capitalize">{{ $activity->getDifficultyLevel() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm opacity-75">Estado</div>
                        <div class="px-3 py-1 rounded-full text-sm font-medium {{ $activity->status == 'active' ? 'bg-green-500' : ($activity->status == 'pending' ? 'bg-yellow-500' : 'bg-red-500') }}">{{ ucfirst($activity->status) }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-b-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <!-- Description -->
                    @if($activity->description)
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                            </svg>
                            Descripción
                        </h2>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <p class="text-gray-700 leading-relaxed">{{ $activity->description }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Activity Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                Detalles de la Actividad
                            </h2>
                            
                            <div class="space-y-4">
                                @if($activity->start_date)
                                <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <div class="font-medium text-gray-900">Fecha de Inicio</div>
                                        <div class="text-blue-600">{{ $activity->start_date->format('l, d \d\e F \d\e Y \a \l\a\s H:i') }}</div>
                                        @if($activity->start_date->isFuture())
                                            <div class="text-sm text-gray-500">{{ $activity->start_date->diffForHumans() }}</div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if($activity->end_date)
                                <div class="flex items-center p-4 bg-purple-50 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <div class="font-medium text-gray-900">Fecha de Finalización</div>
                                        <div class="text-purple-600">{{ $activity->end_date->format('l, d \d\e F \d\e Y \a \l\a\s H:i') }}</div>
                                        @if($activity->start_date && $activity->end_date)
                                            <div class="text-sm text-gray-500">Duración: {{ $activity->start_date->diffInHours($activity->end_date) }} horas</div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if($activity->location)
                                <div class="flex items-center p-4 bg-orange-50 rounded-lg">
                                    <svg class="w-6 h-6 text-orange-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <div class="font-medium text-gray-900">Ubicación</div>
                                        <div class="text-orange-600">{{ $activity->location }}</div>
                                    </div>
                                </div>
                                @endif

                                @if($activity->registration_type)
                                <div class="flex items-center p-4 bg-indigo-50 rounded-lg">
                                    <svg class="w-6 h-6 text-indigo-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-4 4-4-4 4-4 .257-.257A6 6 0 1118 8zm-6-2a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <div class="font-medium text-gray-900">Tipo de Registro</div>
                                        <div class="text-indigo-600">{{ $activity->registration_type == 'open' ? 'Registro Abierto' : 'Requiere Aprobación' }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                </svg>
                                Participación
                            </h2>

                            <!-- Participants Info -->
                            @if($activity->max_participants)
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-gray-700 font-medium">Participantes</span>
                                    <span class="text-2xl font-bold text-blue-600">{{ $activity->current_participants }}/{{ $activity->max_participants }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: {{ $activity->max_participants > 0 ? ($activity->current_participants / $activity->max_participants) * 100 : 0 }}%"></div>
                                </div>
                                @if($activity->isFull())
                                    <p class="text-red-600 text-sm mt-2 font-medium">Actividad completa</p>
                                @else
                                    <p class="text-green-600 text-sm mt-2">{{ $activity->max_participants - $activity->current_participants }} lugares disponibles</p>
                                @endif
                            </div>
                            @endif

                            <!-- Skills Development -->
                            @if($activity->skills->count() > 0)
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-3">Habilidades que Desarrollarás</h3>
                                <div class="space-y-2">
                                    @foreach($activity->skills as $skill)
                                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                        <span class="text-green-800 font-medium">{{ $skill->name }}</span>
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= ($skill->pivot->development_weight ?? 3) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-600">Nivel {{ $skill->pivot->development_weight ?? 3 }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Organizer Info -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-3">Organizador</h3>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ substr($activity->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $activity->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $activity->user->email }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="border-t border-gray-200 pt-8">
                        <div class="flex flex-wrap gap-4 justify-between items-center">
                            <div class="flex space-x-4">
                                <a href="{{ route('activities.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Volver a Actividades
                                </a>
                                
                                @if(auth()->user()->isAdmin())
                                    @if(!$activity->isCompleted())
                                        <a href="{{ route('activities.edit', $activity) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                            </svg>
                                            Editar Actividad
                                        </a>
                                    @endif
                                @endif
                            </div>

                            @if(!auth()->user()->isAdmin())
                                @php
                                    $userJoined = $activity->users->contains(auth()->id());
                                    $participationStatus = $activity->getUserParticipationStatus();
                                @endphp
                                
                                <div class="flex space-x-4">
                                    @if($userJoined)
                                        @if($participationStatus === 'joined')
                                            <div class="inline-flex items-center px-6 py-3 bg-green-100 text-green-800 rounded-lg font-medium">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                Ya estás inscrito
                                            </div>
                                            @if(!in_array($activity->status, ['completed', 'completada']))
                                            <form action="{{ route('calendar.leave', $activity) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-200" onclick="return confirm('¿Estás seguro de que quieres abandonar esta actividad?')">
                                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Abandonar
                                                </button>
                                            </form>
                                            @endif
                                        @elseif($participationStatus === 'completed')
                                            <div class="inline-flex items-center px-6 py-3 bg-blue-100 text-blue-800 rounded-lg font-medium">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Actividad Completada
                                            </div>
                                        @elseif($participationStatus === 'pending')
                                            <div class="inline-flex items-center px-6 py-3 bg-yellow-100 text-yellow-800 rounded-lg font-medium">
                                                <svg class="w-5 h-5 mr-2 animate-spin" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                                </svg>
                                                Solicitud Pendiente
                                            </div>
                                        @endif
                                    @elseif(in_array($activity->status, ['completed', 'completada']))
                                        <div class="inline-flex items-center px-6 py-3 bg-blue-100 text-blue-800 rounded-lg font-medium">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Actividad Completada
                                        </div>
                                    @elseif(!in_array($activity->status, ['abierta', 'active', 'proxima']))
                                        <div class="inline-flex items-center px-6 py-3 bg-red-100 text-red-800 rounded-lg font-medium">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            Actividad no disponible
                                        </div>
                                    @elseif($activity->isFull())
                                        <div class="inline-flex items-center px-6 py-3 bg-orange-100 text-orange-800 rounded-lg font-medium">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                            </svg>
                                            Actividad completa
                                        </div>
                                    @else
                                        <form action="{{ route('calendar.join', $activity) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition duration-200 transform hover:scale-105">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                ¡Unirse a esta Actividad!
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>