<x-app-layout>
    <x-page-header title="Detalle del Ranking" subtitle="Desglose completo de puntos y estadísticas">
        <a href="{{ route('rankings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
            ← Volver al Ranking
        </a>
    </x-page-header>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- User Info -->
            <x-content-card header="Información del Usuario">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 h-16 w-16">
                        <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-xl font-medium text-gray-700">{{ substr($ranking->user->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $ranking->user->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $ranking->user->email }}</p>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ranking->rank == 'Diamante' ? 'bg-purple-100 text-purple-800' : '' }} {{ $ranking->rank == 'Platino' ? 'bg-blue-100 text-blue-800' : '' }} {{ $ranking->rank == 'Oro' ? 'bg-yellow-100 text-yellow-800' : '' }} {{ $ranking->rank == 'Plata' ? 'bg-gray-100 text-gray-800' : '' }} {{ $ranking->rank == 'Bronce' ? 'bg-orange-100 text-orange-800' : '' }}">
                                {{ $ranking->rank }}
                            </span>
                        </div>
                    </div>
                </div>
            </x-content-card>

            <!-- Points Breakdown -->
            <x-content-card header="Desglose de Puntos">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Activities -->
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-medium text-green-900">Actividades Completadas</h4>
                            <span class="text-2xl">🎯</span>
                        </div>
                        <div class="text-sm text-green-700">
                            <div>{{ $ranking->activities_completed }} actividades × 15 puntos</div>
                            <div class="font-semibold">= {{ $ranking->activities_completed * 15 }} puntos</div>
                        </div>
                    </div>

                    <!-- Hours -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-medium text-blue-900">Horas de Voluntariado</h4>
                            <span class="text-2xl">⏰</span>
                        </div>
                        <div class="text-sm text-blue-700">
                            <div>{{ $ranking->hours_volunteered }} horas × 8 puntos</div>
                            <div class="font-semibold">= {{ $ranking->hours_volunteered * 8 }} puntos</div>
                        </div>
                    </div>

                    <!-- Donations -->
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-medium text-purple-900">Donaciones Verificadas</h4>
                            <span class="text-2xl">💝</span>
                        </div>
                        <div class="text-sm text-purple-700">
                            @php
                                $donationPoints = $ranking->user->donations()
                                    ->where('validation_status', 'approved')
                                    ->where('status', 'approved')
                                    ->get()
                                    ->sum(function($donation) {
                                        return $donation->calculatePoints();
                                    });
                            @endphp
                            <div>{{ $ranking->donations_made }} donaciones</div>
                            <div class="font-semibold">= {{ $donationPoints }} puntos</div>
                        </div>
                    </div>

                    <!-- Certificates -->
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-medium text-yellow-900">Certificados Obtenidos</h4>
                            <span class="text-2xl">🏆</span>
                        </div>
                        <div class="text-sm text-yellow-700">
                            <div>{{ $ranking->certificates_earned }} certificados × 75 puntos</div>
                            <div class="font-semibold">= {{ $ranking->certificates_earned * 75 }} puntos</div>
                        </div>
                    </div>
                </div>

                <!-- Total -->
                <div class="mt-6 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border-2 border-indigo-200">
                    <div class="text-center">
                        <div class="text-sm text-gray-600 mb-1">Total de Puntos</div>
                        <div class="text-3xl font-bold text-indigo-600">{{ number_format($ranking->total_points) }}</div>
                        <div class="text-sm text-gray-500 mt-1">Posición #{{ $ranking->rank_position ?? 'N/A' }} en el ranking</div>
                    </div>
                </div>
            </x-content-card>

            <!-- Donation Details -->
            @if($ranking->donations_made > 0)
                <x-content-card header="Detalle de Donaciones">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Puntos</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($ranking->user->donations()->where('validation_status', 'approved')->get() as $donation)
                                <tr>
                                    <td class="px-4 py-2 text-sm">{{ number_format($donation->amount, 2) }} Bs</td>
                                    <td class="px-4 py-2 text-sm">{{ $donation->getCategory() }}</td>
                                    <td class="px-4 py-2 text-sm font-semibold text-green-600">{{ $donation->calculatePoints() }}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $donation->getStatusBadgeColor() }}">
                                            {{ ucfirst($donation->validation_status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-content-card>
            @endif
        </div>
    </div>
</x-app-layout>