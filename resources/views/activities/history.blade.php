@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <x-page-header 
        title="{{ auth()->user()->isAdmin() ? 'Historial de Actividades' : 'Mi Historial de Actividades' }}"
        subtitle="Actividades completadas y canceladas">
        <x-slot name="actions">
            <x-action-button href="{{ route('activities.index') }}" variant="primary">
                <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"></path>
                </svg>
                Actividades Activas
            </x-action-button>
            @if(auth()->user()->isAdmin())
                <x-action-button href="{{ route('activities.create') }}" variant="secondary">
                    <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Crear Nueva Actividad
                </x-action-button>
            @endif
        </x-slot>
    </x-page-header>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($activities->count() > 0)
        <x-content-card class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button id="all-tab" class="tab-button active whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                        Todas ({{ $activities->count() }})
                    </button>
                    <button id="completed-tab" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Completadas ({{ $activities->whereIn('status', ['completed', 'completada'])->count() }})
                    </button>
                    <button id="cancelled-tab" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Canceladas ({{ $activities->whereIn('status', ['cancelled', 'cancelada'])->count() }})
                    </button>
                </nav>
            </div>
        </x-content-card>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($activities as $activity)
                <x-content-card class="activity-card" data-status="{{ $activity->status }}">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-800">{{ $activity->name }}</h3>
                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ in_array($activity->status, ['completed', 'completada']) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ in_array($activity->status, ['completed', 'completada']) ? 'Completada' : 'Cancelada' }}
                        </span>
                    </div>

                    <p class="text-gray-600 mb-4">{{ Str::limit($activity->description ?? 'Sin descripción', 100) }}</p>

                    <div class="space-y-2 mb-4">
                        @if($activity->category)
                            <div class="flex items-center text-sm text-blue-600">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                </svg>
                                {{ ucfirst(str_replace('_', ' ', $activity->category)) }}
                            </div>
                        @endif

                        @if($activity->location)
                            <div class="flex items-center text-sm text-orange-600">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $activity->location }}
                            </div>
                        @endif

                        <div class="flex items-center text-sm text-purple-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            @if($activity->start_date)
                                {{ $activity->start_date->format('d M Y') }}
                                @if($activity->end_date && $activity->end_date != $activity->start_date)
                                    - {{ $activity->end_date->format('d M Y') }}
                                @endif
                            @else
                                Sin fecha definida
                            @endif
                        </div>

                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                            </svg>
                            {{ $activity->current_participants }} participante{{ $activity->current_participants != 1 ? 's' : '' }}
                            @if($activity->max_participants)
                                / {{ $activity->max_participants }}
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <small class="text-gray-500">
                            @if(auth()->user()->isAdmin())
                                Por: {{ $activity->user->name }}
                            @else
                                {{ $activity->created_at->format('d M Y') }}
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
                </x-content-card>
            @endforeach
        </div>
    @else
        <x-content-card>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No hay actividades en el historial</h3>
                <p class="mt-2 text-gray-500">Las actividades completadas y canceladas aparecerán aquí.</p>
                <div class="mt-6">
                    <x-action-button href="{{ route('activities.index') }}" variant="primary">
                        Ver actividades activas
                    </x-action-button>
                </div>
            </div>
        </x-content-card>
    @endif
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const activityCards = document.querySelectorAll('.activity-card');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all tabs
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            // Add active class to clicked tab
            this.classList.add('active', 'border-blue-500', 'text-blue-600');
            this.classList.remove('border-transparent', 'text-gray-500');

            // Filter activities
            const filterStatus = this.id.replace('-tab', '');

            activityCards.forEach(card => {
                const cardContainer = card.closest('.activity-card') || card;
                if (filterStatus === 'all' || cardContainer.dataset.status === filterStatus) {
                    cardContainer.style.display = 'block';
                } else {
                    cardContainer.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endsection