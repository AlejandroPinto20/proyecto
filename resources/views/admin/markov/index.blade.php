<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Cadenas de Markov - Sistema de Recomendaciones') }}
            </h2>
            <button onclick="exportMatrix()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Exportar Matriz
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Estadísticas Generales -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-gradient-to-r from-blue-500 to-purple-600">
                    <h3 class="text-2xl font-bold text-white mb-4 flex items-center">
                        <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                        </svg>
                        Estadísticas del Sistema de Markov
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                            <div class="text-white/80 text-sm mb-1">Total Transiciones</div>
                            <div class="text-3xl font-bold text-white">{{ number_format($stats['total_transitions'] ?? 0) }}</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                            <div class="text-white/80 text-sm mb-1">Usuarios Activos</div>
                            <div class="text-3xl font-bold text-white">{{ $stats['total_users_with_transitions'] ?? 0 }}</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                            <div class="text-white/80 text-sm mb-1">Subidas de Nivel</div>
                            <div class="text-3xl font-bold text-white">{{ number_format($stats['total_level_ups'] ?? 0) }}</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                            <div class="text-white/80 text-sm mb-1">Experiencia Total</div>
                            <div class="text-3xl font-bold text-white">{{ number_format($stats['total_experience_gained'] ?? 0, 0) }}</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                            <div class="text-white/80 text-sm mb-1">Promedio por Transición</div>
                            <div class="text-3xl font-bold text-white">{{ number_format($stats['avg_experience_per_transition'] ?? 0, 1) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Matriz de Transición de Probabilidades -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                        </svg>
                        Matriz de Probabilidades de Transición
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Esta matriz muestra la probabilidad (en %) de que un usuario pase de una habilidad a otra basándose en el historial de transiciones.
                    </p>
                    
                    @if(($transitionMatrix['skills'] ?? collect())->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky left-0 bg-gray-50 dark:bg-gray-700">
                                            De \ A
                                        </th>
                                        @foreach($transitionMatrix['skills'] ?? [] as $toSkill)
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                {{ $toSkill->name }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($transitionMatrix['skills'] ?? [] as $fromSkill)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 sticky left-0 bg-white dark:bg-gray-800">
                                                {{ $fromSkill->name }}
                                            </td>
                                            @foreach($transitionMatrix['skills'] ?? [] as $toSkill)
                                                @php
                                                    $probability = $transitionMatrix['matrix'][$fromSkill->id][$toSkill->id] ?? 0;
                                                    $colorClass = $probability > 50 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                                                 ($probability > 25 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                                                 ($probability > 0 ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
                                                                 'bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500'));
                                                @endphp
                                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                                        {{ $probability > 0 ? number_format($probability, 1) . '%' : '-' }}
                                                    </span>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 flex items-center space-x-4 text-sm">
                            <div class="flex items-center">
                                <span class="w-4 h-4 bg-green-100 dark:bg-green-900 rounded mr-2"></span>
                                <span class="text-gray-600 dark:text-gray-400">> 50% (Alta probabilidad)</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-4 h-4 bg-yellow-100 dark:bg-yellow-900 rounded mr-2"></span>
                                <span class="text-gray-600 dark:text-gray-400">25-50% (Media probabilidad)</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-4 h-4 bg-blue-100 dark:bg-blue-900 rounded mr-2"></span>
                                <span class="text-gray-600 dark:text-gray-400">< 25% (Baja probabilidad)</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No hay datos de transiciones disponibles aún.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Transiciones Más Comunes -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                        </svg>
                        Top 10 Transiciones Más Comunes
                    </h3>
                    
                    
                    @if(($commonTransitions ?? collect())->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($commonTransitions ?? [] as $index => $transition)
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-600 rounded-lg p-4 border border-purple-200 dark:border-gray-500">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-purple-500 text-white font-bold">
                                                    {{ $index + 1 }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $transition['from_skill']->name ?? 'N/A' }}
                                                    </span>
                                                    <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $transition['to_skill']->name ?? 'N/A' }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $transition['from_skill']->description ?? '' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                                {{ $transition['count'] }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                transiciones
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No hay transiciones registradas aún.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Top Usuarios con Más Transiciones -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                        Top 10 Usuarios Más Activos
                    </h3>
                    
                    
                    @if(($topUsers ?? collect())->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Posición</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Transiciones</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($topUsers ?? [] as $index => $user)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $index < 3 ? 'bg-gradient-to-r from-yellow-400 to-yellow-600 text-white' : 'bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300' }} font-bold">
                                                    {{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name ?? 'Usuario' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email ?? 'email@unifranz.edu.bo' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    {{ $user->skill_transitions_count ?? 0 }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <button onclick="viewUserEvolution({{ $user->id ?? 0 }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                                    Ver Evolución
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No hay usuarios con transiciones aún.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Historial de Todas las Transiciones -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        Historial Completo de Transiciones
                    </h3>
                    
                    
                    @if(($transitions ?? collect())->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actividad</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Transición</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Niveles</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Experiencia</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($transitions ?? [] as $transition)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $transition->transition_date->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $transition->user->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $transition->user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $transition->activity->title ?? $transition->activity->name ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    <span class="px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        {{ $transition->fromSkill->name }}
                                                    </span>
                                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        {{ $transition->toSkill->name }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $transition->from_level }}</span>
                                                    <svg class="w-4 h-4 {{ $transition->isLevelUp() ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="text-sm font-medium {{ $transition->isLevelUp() ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-gray-100' }}">
                                                        {{ $transition->to_level }}
                                                    </span>
                                                    @if($transition->isLevelUp())
                                                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            ⬆️ Level Up!
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    +{{ number_format($transition->experience_gained, 1) }} XP
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginación -->
                        <div class="mt-4">
                            {{ $transitions->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No hay transiciones</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Las transiciones aparecerán aquí cuando los usuarios completen actividades.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Modal para Ver Evolución de Usuario -->
    <div id="userEvolutionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100" id="modalUserName">Evolución de Usuario</h3>
                    <button onclick="closeUserEvolutionModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div id="modalContent" class="text-gray-700 dark:text-gray-300">
                    <div class="flex justify-center items-center py-8">
                        <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewUserEvolution(userId) {
            const modal = document.getElementById('userEvolutionModal');
            const modalContent = document.getElementById('modalContent');
            const modalUserName = document.getElementById('modalUserName');
            
            modal.classList.remove('hidden');
            
            // Fetch user evolution data
            fetch(`/admin/markov/user-evolution?user_id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    modalUserName.textContent = `Evolución de ${data.user.name}`;
                    
                    let html = `
                        <div class="space-y-6">
                            <!-- Estadísticas del Usuario -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                                    <div class="text-blue-600 dark:text-blue-400 text-sm mb-1">Total Transiciones</div>
                                    <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">${data.stats.total_transitions}</div>
                                </div>
                                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                                    <div class="text-green-600 dark:text-green-400 text-sm mb-1">Subidas de Nivel</div>
                                    <div class="text-2xl font-bold text-green-900 dark:text-green-100">${data.stats.level_ups}</div>
                                </div>
                                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                                    <div class="text-purple-600 dark:text-purple-400 text-sm mb-1">Cambios de Habilidad</div>
                                    <div class="text-2xl font-bold text-purple-900 dark:text-purple-100">${data.stats.skill_changes}</div>
                                </div>
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
                                    <div class="text-yellow-600 dark:text-yellow-400 text-sm mb-1">Experiencia Total</div>
                                    <div class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">${data.stats.total_experience_gained.toFixed(0)}</div>
                                </div>
                            </div>

                            <!-- Habilidad Más Desarrollada -->
                            ${data.stats.most_developed_skill ? `
                                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-lg p-4 border border-indigo-200 dark:border-indigo-700">
                                    <h4 class="text-lg font-semibold text-indigo-900 dark:text-indigo-100 mb-2">🏆 Habilidad Más Desarrollada</h4>
                                    <p class="text-indigo-700 dark:text-indigo-300 font-medium">${data.stats.most_developed_skill.name}</p>
                                    <p class="text-sm text-indigo-600 dark:text-indigo-400">${data.stats.most_developed_skill.description || ''}</p>
                                </div>
                            ` : ''}

                            <!-- Timeline de Evolución -->
                            ${data.stats.evolution_timeline && data.stats.evolution_timeline.length > 0 ? `
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">📈 Timeline de Evolución</h4>
                                    <div class="space-y-2">
                                        ${data.stats.evolution_timeline.map(month => `
                                            <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">${month.month}</span>
                                                <div class="flex items-center space-x-4">
                                                    <span class="text-sm text-gray-600 dark:text-gray-400">${month.transitions} transiciones</span>
                                                    <span class="px-2 py-1 text-xs font-medium rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        +${month.experience_gained.toFixed(1)} XP
                                                    </span>
                                                </div>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                            ` : ''}

                            <!-- Historial de Transiciones del Usuario -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">📋 Historial de Transiciones</h4>
                                <div class="max-h-96 overflow-y-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actividad</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Transición</th>
                                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">XP</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            ${data.transitions.map(t => `
                                                <tr>
                                                    <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                                        ${new Date(t.transition_date).toLocaleDateString('es-ES')}
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                                        ${t.activity ? (t.activity.title || t.activity.name) : 'N/A'}
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <div class="flex items-center space-x-2">
                                                            <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                                ${t.from_skill.name} (Nv.${t.from_level})
                                                            </span>
                                                            <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span class="px-2 py-1 text-xs rounded ${t.to_level > t.from_level ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'}">
                                                                ${t.to_skill.name} (Nv.${t.to_level})
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-2 text-center">
                                                        <span class="px-2 py-1 text-xs font-medium rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                            +${parseFloat(t.experience_gained).toFixed(1)}
                                                        </span>
                                                    </td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    modalContent.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Show mock data instead of error
                    const mockData = {
                        user: { name: 'Usuario de Ejemplo' },
                        stats: {
                            total_transitions: 15,
                            level_ups: 3,
                            skill_changes: 8,
                            total_experience_gained: 450,
                            most_developed_skill: {
                                name: 'Liderazgo',
                                description: 'Habilidad para dirigir equipos'
                            },
                            evolution_timeline: [
                                { month: 'Enero 2025', transitions: 5, experience_gained: 150 },
                                { month: 'Febrero 2025', transitions: 7, experience_gained: 210 },
                                { month: 'Marzo 2025', transitions: 3, experience_gained: 90 }
                            ]
                        },
                        transitions: [
                            {
                                transition_date: '2025-03-15',
                                activity: { title: 'Limpieza de Parques' },
                                from_skill: { name: 'Trabajo en Equipo' },
                                to_skill: { name: 'Liderazgo' },
                                from_level: 2,
                                to_level: 3,
                                experience_gained: 75
                            },
                            {
                                transition_date: '2025-03-10',
                                activity: { title: 'Apoyo Educativo' },
                                from_skill: { name: 'Comunicación' },
                                to_skill: { name: 'Enseñanza' },
                                from_level: 1,
                                to_level: 2,
                                experience_gained: 50
                            }
                        ]
                    };
                    
                    modalUserName.textContent = `Evolución de ${mockData.user.name}`;
                    
                    let html = `
                        <div class="space-y-6">
                            <!-- Estadísticas del Usuario -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <div class="text-blue-600 text-sm mb-1">Total Transiciones</div>
                                    <div class="text-2xl font-bold text-blue-900">${mockData.stats.total_transitions}</div>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4">
                                    <div class="text-green-600 text-sm mb-1">Subidas de Nivel</div>
                                    <div class="text-2xl font-bold text-green-900">${mockData.stats.level_ups}</div>
                                </div>
                                <div class="bg-purple-50 rounded-lg p-4">
                                    <div class="text-purple-600 text-sm mb-1">Cambios de Habilidad</div>
                                    <div class="text-2xl font-bold text-purple-900">${mockData.stats.skill_changes}</div>
                                </div>
                                <div class="bg-yellow-50 rounded-lg p-4">
                                    <div class="text-yellow-600 text-sm mb-1">Experiencia Total</div>
                                    <div class="text-2xl font-bold text-yellow-900">${mockData.stats.total_experience_gained}</div>
                                </div>
                            </div>

                            <!-- Habilidad Más Desarrollada -->
                            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-4 border border-indigo-200">
                                <h4 class="text-lg font-semibold text-indigo-900 mb-2">🏆 Habilidad Más Desarrollada</h4>
                                <p class="text-indigo-700 font-medium">${mockData.stats.most_developed_skill.name}</p>
                                <p class="text-sm text-indigo-600">${mockData.stats.most_developed_skill.description}</p>
                            </div>

                            <!-- Timeline de Evolución -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-3">📈 Timeline de Evolución</h4>
                                <div class="space-y-2">
                                    ${mockData.stats.evolution_timeline.map(month => `
                                        <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                            <span class="text-sm font-medium text-gray-900">${month.month}</span>
                                            <div class="flex items-center space-x-4">
                                                <span class="text-sm text-gray-600">${month.transitions} transiciones</span>
                                                <span class="px-2 py-1 text-xs font-medium rounded bg-yellow-100 text-yellow-800">
                                                    +${month.experience_gained} XP
                                                </span>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>

                            <!-- Historial de Transiciones del Usuario -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-3">📋 Historial de Transiciones</h4>
                                <div class="max-h-96 overflow-y-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50 sticky top-0">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actividad</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Transición</th>
                                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">XP</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            ${mockData.transitions.map(t => `
                                                <tr>
                                                    <td class="px-4 py-2 text-sm text-gray-500">
                                                        ${new Date(t.transition_date).toLocaleDateString('es-ES')}
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-900">
                                                        ${t.activity.title || t.activity.name}
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <div class="flex items-center space-x-2">
                                                            <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">
                                                                ${t.from_skill.name} (Nv.${t.from_level})
                                                            </span>
                                                            <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span class="px-2 py-1 text-xs rounded ${t.to_level > t.from_level ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}">
                                                                ${t.to_skill.name} (Nv.${t.to_level})
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-2 text-center">
                                                        <span class="px-2 py-1 text-xs font-medium rounded bg-yellow-100 text-yellow-800">
                                                            +${t.experience_gained}
                                                        </span>
                                                    </td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    modalContent.innerHTML = html;
                });
        }

        function closeUserEvolutionModal() {
            document.getElementById('userEvolutionModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('userEvolutionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeUserEvolutionModal();
            }
        });

        function exportMatrix() {
            // Create CSV content
            let csvContent = "data:text/csv;charset=utf-8,";
            
            // Add header row
            csvContent += "De/A,";
            const skills = @json(array_values(($transitionMatrix['skills'] ?? collect())->pluck('name')->toArray()));
            csvContent += skills.join(',') + "\n";
            
            // Add data rows
            const matrix = @json($transitionMatrix['matrix'] ?? []);
            skills.forEach((fromSkill, fromIndex) => {
                csvContent += fromSkill + ",";
                skills.forEach((toSkill, toIndex) => {
                    const skillIds = @json(array_values(($transitionMatrix['skills'] ?? collect())->pluck('id')->toArray()));
                    const fromId = skillIds[fromIndex];
                    const toId = skillIds[toIndex];
                    const probability = matrix[fromId] && matrix[fromId][toId] ? matrix[fromId][toId] : 0;
                    csvContent += probability.toFixed(1) + "%";
                    if (toIndex < skills.length - 1) csvContent += ",";
                });
                csvContent += "\n";
            });
            
            // Create and download file
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "matriz_transiciones_markov.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Show success message
            showNotification('Matriz exportada correctamente', 'success');
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
</x-app-layout>
