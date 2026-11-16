<div class="activity-details">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
        {{ $activity->name }}
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Activity Info -->
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Información General</h3>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-2">
                    <p><strong>Organizador:</strong> {{ $activity->user->name }}</p>
                    <p><strong>Estado:</strong>
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            @if($activity->status === 'active') bg-green-100 text-green-800
                            @elseif($activity->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($activity->status === 'completed') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($activity->status) }}
                        </span>
                    </p>
                    @if($activity->location)
                        <p><strong>Ubicación:</strong> {{ $activity->location }}</p>
                    @endif
                    @if($activity->max_participants)
                        <p><strong>Participantes:</strong> {{ $activity->users->count() }} / {{ $activity->max_participants }}</p>
                    @else
                        <p><strong>Participantes:</strong> {{ $activity->users->count() }} (sin límite)</p>
                    @endif
                </div>
            </div>

            @if($activity->description)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Descripción</h3>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <p class="text-gray-700 dark:text-gray-300">{{ $activity->description }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Schedule Info -->
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Horario</h3>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-2">
                    <p><strong>Fecha de inicio:</strong> {{ $activity->start_date->format('d/m/Y H:i') }}</p>
                    <p><strong>Fecha de fin:</strong> {{ $activity->end_date->format('d/m/Y H:i') }}</p>
                    <p><strong>Duración:</strong> {{ $activity->start_date->diffInHours($activity->end_date) }} horas</p>
                </div>
            </div>

            <!-- Participants List -->
            @if($activity->users->count() > 0)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    Participantes ({{ $activity->users->count() }})
                </h3>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 max-h-48 overflow-y-auto">
                    <ul class="space-y-1">
                        @foreach($activity->users as $participant)
                        <li class="flex items-center justify-between">
                            <span class="text-gray-700 dark:text-gray-300">{{ $participant->name }}</span>
                            @if($participant->id === auth()->id())
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Tú</span>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons (for modal) -->
    <div class="mt-6 flex justify-end space-x-3">
        @if(auth()->check())
            @php
                $isParticipating = $activity->users()->where('user_id', auth()->id())->exists();
                $canJoin = $activity->canUserJoin(auth()->user());
                $canLeave = $activity->canUserLeave(auth()->user());
            @endphp

            @if($isParticipating)
                @if($canLeave)
                    <button class="leave-btn px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg font-medium">
                        <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        Abandonar Actividad
                    </button>
                @else
                    <div class="px-6 py-3 bg-gradient-to-r from-gray-400 to-gray-500 text-white rounded-lg font-medium shadow-lg opacity-50 cursor-not-allowed">
                        <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                        </svg>
                        No se puede abandonar
                    </div>
                @endif
            @else
                @if($canJoin)
                    <button class="join-btn px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg font-medium">
                        <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Unirse a la Actividad
                    </button>
                @else
                    @if($activity->isCompleted())
                        <div class="px-6 py-3 bg-gradient-to-r from-gray-400 to-gray-500 text-white rounded-lg font-medium shadow-lg opacity-50 cursor-not-allowed">
                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Actividad Completada
                        </div>
                    @elseif($activity->isCancelled())
                        <div class="px-6 py-3 bg-gradient-to-r from-red-400 to-red-500 text-white rounded-lg font-medium shadow-lg opacity-50 cursor-not-allowed">
                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Actividad Cancelada
                        </div>
                    @elseif($activity->isFull())
                        <div class="px-6 py-3 bg-gradient-to-r from-gray-400 to-gray-500 text-white rounded-lg font-medium shadow-lg opacity-50 cursor-not-allowed">
                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                            Actividad Llena
                        </div>
                    @else
                        <div class="px-6 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white rounded-lg font-medium shadow-lg opacity-50 cursor-not-allowed">
                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            No disponible
                        </div>
                    @endif
                @endif
            @endif
        @endif
    </div>
</div>