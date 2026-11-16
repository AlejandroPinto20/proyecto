<x-app-layout>
    <x-page-header title="Rankings y Puntos" subtitle="Visualiza tu progreso y compite con otros voluntarios">
        <div class="flex items-center space-x-3">
            @if(Auth::user()->isAdmin())
                <form method="POST" action="{{ route('rankings.update-all') }}" class="inline">
                    @csrf
                    <x-action-button type="submit" variant="primary" size="sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Actualizar Rankings
                    </x-action-button>
                </form>
            @endif
            <div class="text-sm text-gray-500">
                Última actualización: {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>
    </x-page-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Current User Ranking -->
            @if($userRanking)
                <x-content-card header="Tu Ranking Actual">
                    <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">#{{ $userRanking->rank_position ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-600">Posición</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ number_format($userRanking->total_points) }}</div>
                            <div class="text-sm text-gray-600">Puntos Totales</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $userRanking->rank }}</div>
                            <div class="text-sm text-gray-600">Rango</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ $userRanking->hours_volunteered }}</div>
                            <div class="text-sm text-gray-600">Horas</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-indigo-600">{{ $userRanking->activities_completed }}</div>
                            <div class="text-sm text-gray-600">Actividades</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-600">{{ $userRanking->getUnlockedAchievementsCount() }}</div>
                            <div class="text-sm text-gray-600">Logros 🏆</div>
                        </div>
                    </div>
                    
                    @if($userRanking->total_points == 0)
                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                <strong>¡Comienza tu aventura de voluntariado!</strong> 
                                Participa en actividades, haz donaciones y obtén certificados para ganar puntos y subir en el ranking.
                            </p>
                        </div>
                    @endif
                </x-content-card>
            @else
                <x-content-card header="Tu Ranking">
                    <div class="text-center py-8">
                        <div class="text-gray-500 mb-4">
                            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">¡Únete al ranking!</h3>
                        <p class="text-gray-600">Participa en actividades de voluntariado para aparecer en el ranking y ganar puntos.</p>
                    </div>
                </x-content-card>
            @endif



            <x-content-card header="Top Voluntarios">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posición</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voluntario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Puntos</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rango</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actividades</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donaciones</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Certificados</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logros</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($rankings as $ranking)
                            <tr class="{{ $userRanking && $ranking->user_id === $userRanking->user_id ? 'bg-blue-50 border-l-4 border-blue-400' : '' }} hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-900">
                                            #{{ $ranking->rank_position ?? $loop->iteration }}
                                        </div>
                                        @if(($ranking->rank_position ?? $loop->iteration) <= 3)
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ ($ranking->rank_position ?? $loop->iteration) == 1 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ ($ranking->rank_position ?? $loop->iteration) == 2 ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ ($ranking->rank_position ?? $loop->iteration) == 3 ? 'bg-orange-100 text-orange-800' : '' }}">
                                            @if(($ranking->rank_position ?? $loop->iteration) == 1) 🥇
                                            @elseif(($ranking->rank_position ?? $loop->iteration) == 2) 🥈
                                            @elseif(($ranking->rank_position ?? $loop->iteration) == 3) 🥉
                                            @endif
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">{{ substr($ranking->user->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('rankings.show', $ranking) }}" class="hover:text-blue-600 hover:underline">
                                                    {{ $ranking->user->name }}
                                                </a>
                                            </div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($ranking->user->email, 25) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                    {{ number_format($ranking->total_points) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $ranking->rank == 'Diamante' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $ranking->rank == 'Platino' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $ranking->rank == 'Oro' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $ranking->rank == 'Plata' ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $ranking->rank == 'Bronce' ? 'bg-orange-100 text-orange-800' : '' }}">
                                        @if($ranking->rank == 'Diamante') 💎
                                        @elseif($ranking->rank == 'Platino') 🏆
                                        @elseif($ranking->rank == 'Oro') 🥇
                                        @elseif($ranking->rank == 'Plata') 🥈
                                        @else 🥉
                                        @endif
                                        {{ $ranking->rank }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="font-medium">{{ $ranking->activities_completed }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="font-medium">{{ $ranking->hours_volunteered }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="font-medium">{{ $ranking->donations_made }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="font-medium">{{ $ranking->certificates_earned }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center space-x-1">
                                        <span class="font-medium">{{ $ranking->getUnlockedAchievementsCount() }}</span>
                                        <span class="text-xs text-gray-500">/17</span>
                                        @if($ranking->getUnlockedAchievementsCount() >= 10)
                                            <span class="text-yellow-500" title="Coleccionista de logros">🏆</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay rankings disponibles</h3>
                                        <p class="text-gray-600">Los rankings aparecerán cuando los usuarios comiencen a participar en actividades.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-content-card>

            <x-content-card header="Sistema de Puntos">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-lg">
                        <h5 class="font-semibold mb-3 text-blue-900 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            Cómo ganar puntos:
                        </h5>
                        <div class="space-y-3 text-sm">
                            <div>
                                <div class="flex items-center text-gray-700 mb-1">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-3"></span>
                                    <strong>15 puntos</strong> por actividad completada
                                </div>
                                <div class="flex items-center text-gray-700">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-3"></span>
                                    <strong>8 puntos</strong> por hora de voluntariado
                                </div>
                            </div>
                            
                            <div>
                                <div class="font-medium text-gray-800 mb-2">Donaciones por rango:</div>
                                <div class="space-y-1 ml-3 text-xs">
                                    <div class="flex justify-between">
                                        <span>💵 Micro (1-50 Bs)</span>
                                        <span class="font-medium">10 pts</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>💰 Pequeña (51-200 Bs)</span>
                                        <span class="font-medium">25 pts</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>🏦 Mediana (201-500 Bs)</span>
                                        <span class="font-medium">50 pts</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>🏢 Grande (501-1000 Bs)</span>
                                        <span class="font-medium">100 pts</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>🏤 Corporativa (1001+ Bs)</span>
                                        <span class="font-medium">200 pts</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center text-gray-700">
                                <span class="w-2 h-2 bg-yellow-400 rounded-full mr-3"></span>
                                <strong>75 puntos</strong> por certificado obtenido
                            </div>
                            
                            <div class="flex items-center text-gray-700">
                                <span class="w-2 h-2 bg-purple-400 rounded-full mr-3"></span>
                                <strong>Puntos bonus</strong> por logros desbloqueados
                            </div>
                            
                            <div class="mt-3 p-2 bg-purple-50 rounded text-xs">
                                <div class="font-medium text-purple-800 mb-1">🏆 Sistema de Logros:</div>
                                <div class="text-purple-700">
                                    Desbloquea logros completando actividades, desarrollando habilidades y contribuyendo.
                                    Cada logro otorga puntos bonus adicionales.
                                </div>
                            </div>
                            
                            <div class="mt-2 p-2 bg-blue-50 rounded text-xs">
                                <div class="font-medium text-blue-800 mb-1">ℹ️ Validación de Donaciones:</div>
                                <div class="text-blue-700">
                                    Las donaciones deben ser verificadas por administradores antes de otorgar puntos.
                                    Se requiere comprobante de transferencia.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-4 rounded-lg">
                        <h5 class="font-semibold mb-3 text-purple-900 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                            Rangos disponibles:
                        </h5>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center justify-between">
                                <span class="flex items-center">
                                    <span class="text-lg mr-2">🥉</span>
                                    <span class="font-medium text-orange-700">Bronce</span>
                                </span>
                                <span class="text-gray-600">0 - 599 pts</span>
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="flex items-center">
                                    <span class="text-lg mr-2">🥈</span>
                                    <span class="font-medium text-gray-700">Plata</span>
                                </span>
                                <span class="text-gray-600">600 - 1,499 pts</span>
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="flex items-center">
                                    <span class="text-lg mr-2">🥇</span>
                                    <span class="font-medium text-yellow-700">Oro</span>
                                </span>
                                <span class="text-gray-600">1,500 - 3,999 pts</span>
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="flex items-center">
                                    <span class="text-lg mr-2">🏆</span>
                                    <span class="font-medium text-blue-700">Platino</span>
                                </span>
                                <span class="text-gray-600">4,000 - 7,999 pts</span>
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="flex items-center">
                                    <span class="text-lg mr-2">💎</span>
                                    <span class="font-medium text-purple-700">Diamante</span>
                                </span>
                                <span class="text-gray-600">8,000+ pts</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </x-content-card>
        </div>
    </div>
</x-app-layout>
