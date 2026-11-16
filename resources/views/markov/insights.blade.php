<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header title="Análisis de Recomendaciones" subtitle="Insights basados en Cadenas de Markov">
                <x-slot name="actions">
                    <button onclick="refreshRecommendations()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                        </svg>
                        Actualizar
                    </button>
                </x-slot>
            </x-page-header>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Statistics Cards -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Activity Overview -->
                    <x-content-card>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Resumen de Actividades</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">{{ $insights['total_activities'] }}</div>
                                <div class="text-sm text-gray-600">Total Actividades</div>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">{{ count($insights['preferred_categories']) }}</div>
                                <div class="text-sm text-gray-600">Categorías Activas</div>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600">{{ count($insights['skill_development']) }}</div>
                                <div class="text-sm text-gray-600">Habilidades</div>
                            </div>
                            <div class="text-center p-4 bg-orange-50 rounded-lg">
                                <div class="text-2xl font-bold text-orange-600">{{ round($insights['activity_frequency']['daily_average'], 1) }}</div>
                                <div class="text-sm text-gray-600">Promedio Diario</div>
                            </div>
                        </div>
                    </x-content-card>

                    <!-- Smart Recommendations -->
                    <x-content-card>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">🤖 Recomendaciones</h3>
                        @if($recommendations->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($recommendations->take(6) as $activity)
                                    <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="font-medium text-gray-900">{{ $activity->name }}</h4>
                                            <span class="text-xs bg-blue-500 text-white px-2 py-1 rounded-full">
                                                +{{ $activity->getEstimatedPoints() }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($activity->description, 80) }}</p>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-blue-600 font-medium">
                                                {{ ucfirst(str_replace('_', ' ', $activity->category)) }}
                                            </span>
                                            <a href="{{ route('activities.show', $activity) }}" 
                                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition duration-200">
                                                Ver Detalles
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">
                                Participa en más actividades para obtener recomendaciones personalizadas
                            </p>
                        @endif
                    </x-content-card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Activity Trend -->
                    <x-content-card>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">📈 Tendencia</h3>
                        <div class="text-center">
                            @php
                                $trend = $insights['activity_frequency']['recent_trend'];
                                $trendColor = $trend === 'increasing' ? 'text-green-600' : ($trend === 'decreasing' ? 'text-red-600' : 'text-blue-600');
                                $trendIcon = $trend === 'increasing' ? '📈' : ($trend === 'decreasing' ? '📉' : '📊');
                                $trendText = $trend === 'increasing' ? 'Creciente' : ($trend === 'decreasing' ? 'Decreciente' : 'Estable');
                            @endphp
                            <div class="text-4xl mb-2">{{ $trendIcon }}</div>
                            <div class="text-lg font-semibold {{ $trendColor }}">{{ $trendText }}</div>
                        </div>
                    </x-content-card>

                    <!-- Skill Development -->
                    <x-content-card>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">🚀 Habilidades</h3>
                        @if(count($insights['skill_development']) > 0)
                            <div class="space-y-3">
                                @foreach(array_slice($insights['skill_development'], 0, 5) as $skill)
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="font-medium text-sm">{{ $skill['name'] }}</span>
                                            <span class="text-xs text-gray-500">Nivel {{ $skill['level'] }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($skill['level'] / 5) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4 text-sm">No hay habilidades registradas</p>
                        @endif
                    </x-content-card>
                </div>
            </div>
        </div>
    </div>

    <script>
    function refreshRecommendations() {
        fetch('{{ route('markov.refresh') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            }
        })
        .catch(error => {
            showToast('Error al actualizar recomendaciones', 'error');
        });
    }
    </script>
</x-app-layout>