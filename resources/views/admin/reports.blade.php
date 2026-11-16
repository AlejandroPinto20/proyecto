<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-gradient-to-r from-green-500 to-blue-600 rounded-full">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Reportes y Estadísticas') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Panel de análisis y métricas del sistema UniFránz
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-white via-orange-50 to-red-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Estadísticas Generales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                <x-content-card class="bg-gradient-to-br from-orange-100 to-red-100 border-orange-200">
                    <div class="text-center py-2">
                        <div class="flex justify-center mb-2">
                            <div class="p-2 bg-gradient-to-r from-orange-500 to-red-500 rounded-full">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs font-medium text-gray-600 mb-1">Total Usuarios</p>
                        <p class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($roleStats->sum('count')) }}</p>
                        <p class="text-xs text-green-600">+{{ number_format($newUsersThisMonth ?? 0) }} este mes</p>
                    </div>
                </x-content-card>

                <x-content-card class="bg-gradient-to-br from-green-100 to-blue-100 border-green-200">
                    <div class="text-center py-2">
                        <div class="flex justify-center mb-2">
                            <div class="p-2 bg-gradient-to-r from-green-500 to-blue-500 rounded-full">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs font-medium text-gray-600 mb-1">Total Donaciones</p>
                        <p class="text-xl font-bold text-gray-900 mb-1">Bs. {{ number_format($donationStats['total_donation_amount'], 2) }}</p>
                        <p class="text-xs text-green-600">{{ $donationStats['total_donations'] }} donaciones</p>
                    </div>
                </x-content-card>

                <x-content-card class="bg-gradient-to-br from-blue-100 to-purple-100 border-blue-200">
                    <div class="text-center py-2">
                        <div class="flex justify-center mb-2">
                            <div class="p-2 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs font-medium text-gray-600 mb-1">Certificados</p>
                        <p class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($certificateStats['total_certificates']) }}</p>
                        <p class="text-xs text-blue-600">{{ number_format($certificateStats['total_hours']) }} horas</p>
                    </div>
                </x-content-card>

                <x-content-card class="bg-gradient-to-br from-purple-100 to-pink-100 border-purple-200">
                    <div class="text-center py-2">
                        <div class="flex justify-center mb-2">
                            <div class="p-2 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs font-medium text-gray-600 mb-1">Actividades</p>
                        <p class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($totalActivities ?? 0) }}</p>
                        <p class="text-xs text-purple-600">{{ number_format($activeActivities ?? 0) }} activas</p>
                    </div>
                </x-content-card>

                <x-content-card class="bg-gradient-to-br from-indigo-100 to-cyan-100 border-indigo-200">
                    <div class="text-center py-2">
                        <div class="flex justify-center mb-2">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-cyan-500 rounded-full">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs font-medium text-gray-600 mb-1">Participaciones</p>
                        <p class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($totalParticipations ?? 0) }}</p>
                        <p class="text-xs text-indigo-600">{{ number_format($pendingParticipations ?? 0) }} pendientes</p>
                    </div>
                </x-content-card>

                <x-content-card class="bg-gradient-to-br from-teal-100 to-green-100 border-teal-200">
                    <div class="text-center py-2">
                        <div class="flex justify-center mb-2">
                            <div class="p-2 bg-gradient-to-r from-teal-500 to-green-500 rounded-full">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs font-medium text-gray-600 mb-1">Promedio Horas</p>
                        <p class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($averageHours ?? 0, 1) }}</p>
                        <p class="text-xs text-teal-600">por voluntario</p>
                    </div>
                </x-content-card>
            </div>



            <!-- Sección de Gráficos y Análisis -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                <!-- Gráfico de Actividades por Mes -->
                <x-content-card>
                    <x-slot name="header">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Actividades por Mes</h3>
                                <p class="text-sm text-gray-600">Muestra el número de actividades creadas cada mes en {{ date('Y') }}</p>
                            </div>
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                    </x-slot>
                    <div class="h-64">
                        <canvas id="activitiesChart"></canvas>
                    </div>
                </x-content-card>

                <!-- Gráfico de Donaciones por Mes -->
                <x-content-card>
                    <x-slot name="header">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Donaciones por Mes</h3>
                                <p class="text-sm text-gray-600">Monto total recaudado en bolivianos por mes durante {{ date('Y') }}</p>
                            </div>
                            <div class="p-3 bg-gradient-to-r from-green-500 to-blue-500 rounded-full">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                    </x-slot>
                    <div class="h-64">
                        <canvas id="donationsChart"></canvas>
                    </div>
                </x-content-card>

                <!-- Gráfico de Crecimiento de Usuarios -->
                <x-content-card>
                    <x-slot name="header">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Crecimiento de Usuarios</h3>
                                <p class="text-sm text-gray-600">Número de nuevos voluntarios y estudiantes registrados cada mes</p>
                            </div>
                            <div class="p-3 bg-gradient-to-r from-orange-500 to-red-500 rounded-full">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                    </x-slot>
                    <div class="h-64">
                        <canvas id="userGrowthChart"></canvas>
                    </div>
                </x-content-card>
            </div>

            <!-- Sección de Análisis Avanzado -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Métricas de Participación -->
                <x-content-card>
                    <x-slot name="header">
                        <h3 class="text-xl font-bold text-gray-900">Métricas de Participación</h3>
                    </x-slot>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tasa de Participación</p>
                                <p class="text-2xl font-bold text-blue-600">78.5%</p>
                            </div>
                            <div class="w-16 h-16">
                                <canvas id="participationGauge"></canvas>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-teal-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Retención de Voluntarios</p>
                                <p class="text-2xl font-bold text-green-600">85.2%</p>
                            </div>
                            <div class="w-16 h-16">
                                <canvas id="retentionGauge"></canvas>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Satisfacción Promedio</p>
                                <p class="text-2xl font-bold text-purple-600">4.3/5</p>
                            </div>
                            <div class="flex space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                </x-content-card>

                <!-- Tendencias y Predicciones -->
                <x-content-card>
                    <x-slot name="header">
                        <h3 class="text-xl font-bold text-gray-900">Tendencias y Predicciones</h3>
                    </x-slot>
                    <div class="space-y-6">
                        <div class="p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                <h4 class="font-semibold text-gray-900">Proyección Próximo Mes</h4>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">Nuevos Voluntarios Estimados</p>
                            <p class="text-xl font-bold text-yellow-600">+24</p>
                        </div>
                        <div class="p-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-lg border border-green-200">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <h4 class="font-semibold text-gray-900">Meta de Donaciones</h4>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">Progreso hacia meta mensual</p>
                            <div class="flex items-center space-x-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: 67%"></div>
                                </div>
                                <span class="text-sm font-medium text-green-600">67.3%</span>
                            </div>
                        </div>
                        <div class="p-4 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg border border-purple-200">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <h4 class="font-semibold text-gray-900">Eficiencia del Sistema</h4>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">Score de rendimiento general</p>
                            <p class="text-xl font-bold text-purple-600">92.8/100</p>
                        </div>
                    </div>
                </x-content-card>
            </div>

            <!-- Sección de Distribuciones -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                <!-- Gráfico de Distribución por Roles -->
                <x-content-card>
                    <x-slot name="header">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Distribución por Roles</h3>
                                <p class="text-sm text-gray-600">Porcentaje de usuarios según su rol: voluntarios, estudiantes y administradores</p>
                            </div>
                            <div class="p-3 bg-gradient-to-r from-orange-500 to-red-500 rounded-full">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </x-slot>
                    <div class="h-64">
                        <canvas id="rolesChart"></canvas>
                    </div>
                </x-content-card>

                <!-- Gráfico de Estado de Donaciones -->
                <x-content-card>
                    <x-slot name="header">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Estado de Donaciones</h3>
                                <p class="text-sm text-gray-600">Proporción de donaciones pendientes, aprobadas, completadas y canceladas</p>
                            </div>
                            <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </x-slot>
                    <div class="h-64">
                        <canvas id="donationStatusChart"></canvas>
                    </div>
                </x-content-card>

                <!-- Gráfico de Actividades por Categoría -->
                <x-content-card>
                    <x-slot name="header">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Actividades por Categoría</h3>
                                <p class="text-sm text-gray-600">Cantidad de actividades según tipo: medio ambiente, salud, educación, social y comunitario</p>
                            </div>
                            <div class="p-3 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        </div>
                    </x-slot>
                    <div class="h-64">
                        <canvas id="activitiesCategoryChart"></canvas>
                    </div>
                </x-content-card>
            </div>

            <!-- Reportes Detallados -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Reporte de Actividad Reciente -->
                <x-content-card>
                    <x-slot name="header">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-900">Actividad Reciente del Sistema</h3>
                            <x-action-button variant="secondary" size="sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Exportar
                            </x-action-button>
                        </div>
                    </x-slot>
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @forelse($recentActivity ?? [] as $activity)
                        <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $activity['action'] ?? 'Acción del sistema' }}</p>
                                <p class="text-sm text-gray-500">{{ $activity['description'] ?? 'Descripción de la actividad' }}</p>
                                <p class="text-xs text-gray-400">{{ $activity['time'] ?? 'Hace unos minutos' }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="mt-2 text-sm">No hay actividad reciente registrada</p>
                        </div>
                        @endforelse
                    </div>
                </x-content-card>

                <!-- Alertas y Notificaciones del Sistema -->
                <x-content-card>
                    <x-slot name="header">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-900">Alertas del Sistema</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ count($systemAlerts ?? []) }} alertas
                            </span>
                        </div>
                    </x-slot>
                    <div class="space-y-4">
                        @forelse($systemAlerts ?? [] as $alert)
                        <div class="p-4 border-l-4 {{ $alert['type'] === 'error' ? 'border-red-400 bg-red-50' : ($alert['type'] === 'warning' ? 'border-yellow-400 bg-yellow-50' : 'border-blue-400 bg-blue-50') }}">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    @if($alert['type'] === 'error')
                                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @elseif($alert['type'] === 'warning')
                                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium {{ $alert['type'] === 'error' ? 'text-red-800' : ($alert['type'] === 'warning' ? 'text-yellow-800' : 'text-blue-800') }}">
                                        {{ $alert['title'] ?? 'Alerta del Sistema' }}
                                    </h4>
                                    <p class="text-sm {{ $alert['type'] === 'error' ? 'text-red-700' : ($alert['type'] === 'warning' ? 'text-yellow-700' : 'text-blue-700') }}">
                                        {{ $alert['message'] ?? 'Mensaje de la alerta' }}
                                    </p>
                                    <p class="text-xs {{ $alert['type'] === 'error' ? 'text-red-600' : ($alert['type'] === 'warning' ? 'text-yellow-600' : 'text-blue-600') }} mt-1">
                                        {{ $alert['time'] ?? 'Hace unos minutos' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-green-600">No hay alertas activas</p>
                            <p class="text-xs text-gray-500">El sistema funciona correctamente</p>
                        </div>
                        @endforelse
                    </div>
                </x-content-card>
            </div>

            <!-- Sección de Rankings y Top Listas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Top Actividades -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Actividades Más Populares</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Ranking por participación</p>
                            </div>
                            <div class="p-3 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="space-y-4">
                            @forelse($topActivities as $index => $activity)
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-orange-50 to-red-50 dark:from-gray-700 dark:to-gray-600 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ Str::limit($activity->title ?? $activity->name, 30) }}</h4>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $activity->users_count }} participantes</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $activity->users_count }}</div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                <p class="mt-2 text-sm">No hay datos de actividades disponibles</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Top Donadores -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Top Donadores</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Ranking por monto total donado</p>
                            </div>
                            <div class="p-3 bg-gradient-to-r from-green-500 to-teal-500 rounded-full">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="space-y-4">
                            @forelse($topDonors as $index => $donor)
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-blue-50 dark:from-gray-700 dark:to-gray-600 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        @if($donor->profile_photo_path)
                                            <img class="h-10 w-10 rounded-full object-cover border-2 border-white dark:border-gray-600" src="{{ asset('storage/' . $donor->profile_photo_path) }}" alt="">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-green-500 to-blue-500 flex items-center justify-center text-white font-bold">
                                                {{ substr($donor->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ Str::limit($donor->name, 25) }}</h4>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $donor->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-900 dark:text-white">Bs. {{ number_format($donor->donations_sum_amount ?? 0, 2) }}</div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                <p class="mt-2 text-sm">No hay datos de donaciones disponibles</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Scripts para Gráficos -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Datos para gráficos
                const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

                // Gráfico de Actividades por Mes
                const activitiesData = @json($monthlyActivityStats->pluck('count', 'month')->toArray());
                const activitiesCtx = document.getElementById('activitiesChart').getContext('2d');
                new Chart(activitiesCtx, {
                    type: 'line',
                    data: {
                        labels: monthNames,
                        datasets: [{
                            label: 'Actividades',
                            data: monthNames.map((_, index) => activitiesData[index + 1] || 0),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: 'rgb(59, 130, 246)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: 'rgb(59, 130, 246)',
                                borderWidth: 1
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0, 0, 0, 0.1)' },
                                ticks: { color: '#6B7280' }
                            },
                            x: {
                                grid: { color: 'rgba(0, 0, 0, 0.1)' },
                                ticks: { color: '#6B7280' }
                            }
                        }
                    }
                });

                // Gráfico de Donaciones por Mes
                const donationsData = @json($monthlyDonationStats->pluck('total_amount', 'month')->toArray());
                const donationsCtx = document.getElementById('donationsChart').getContext('2d');
                new Chart(donationsCtx, {
                    type: 'bar',
                    data: {
                        labels: monthNames,
                        datasets: [{
                            label: 'Monto (Bs.)',
                            data: monthNames.map((_, index) => donationsData[index + 1] || 0),
                            backgroundColor: 'rgba(34, 197, 94, 0.8)',
                            borderColor: 'rgb(34, 197, 94)',
                            borderWidth: 1,
                            borderRadius: 4,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                callbacks: {
                                    label: function(context) {
                                        return 'Bs. ' + context.parsed.y.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0, 0, 0, 0.1)' },
                                ticks: {
                                    color: '#6B7280',
                                    callback: function(value) {
                                        return 'Bs. ' + value.toLocaleString();
                                    }
                                }
                            },
                            x: {
                                grid: { color: 'rgba(0, 0, 0, 0.1)' },
                                ticks: { color: '#6B7280' }
                            }
                        }
                    }
                });

                // Gráfico de Crecimiento de Usuarios
                const userGrowthData = @json($userGrowthStats ?? []);
                const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
                new Chart(userGrowthCtx, {
                    type: 'bar',
                    data: {
                        labels: monthNames,
                        datasets: [{
                            label: 'Nuevos Usuarios',
                            data: monthNames.map((_, index) => userGrowthData[index + 1] || 0),
                            backgroundColor: 'rgba(249, 115, 22, 0.8)',
                            borderColor: 'rgb(249, 115, 22)',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0, 0, 0, 0.1)' },
                                ticks: { color: '#6B7280' }
                            },
                            x: {
                                grid: { color: 'rgba(0, 0, 0, 0.1)' },
                                ticks: { color: '#6B7280' }
                            }
                        }
                    }
                });

                // Gráfico de Distribución por Roles
                const roleData = @json($roleStats->pluck('count', 'role')->toArray());
                const rolesCtx = document.getElementById('rolesChart').getContext('2d');
                new Chart(rolesCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(roleData).map(role => role.charAt(0).toUpperCase() + role.slice(1)),
                        datasets: [{
                            data: Object.values(roleData),
                            backgroundColor: [
                                'rgba(249, 115, 22, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(34, 197, 94, 0.8)',
                                'rgba(168, 85, 247, 0.8)'
                            ],
                            borderColor: [
                                'rgb(249, 115, 22)',
                                'rgb(59, 130, 246)',
                                'rgb(34, 197, 94)',
                                'rgb(168, 85, 247)'
                            ],
                            borderWidth: 2,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Distribución de Usuarios por Rol',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                color: '#374151',
                                padding: 20
                            },
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    color: '#6B7280'
                                }
                            }
                        }
                    }
                });

                // Gráfico de Estado de Donaciones
                const donationStats = @json($donationStats);
                const donationStatusCtx = document.getElementById('donationStatusChart').getContext('2d');
                new Chart(donationStatusCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Pendientes', 'Aprobadas', 'Completadas', 'Canceladas'],
                        datasets: [{
                            data: [
                                donationStats.pending_donations,
                                donationStats.approved_donations,
                                donationStats.completed_donations,
                                donationStats.total_donations - donationStats.pending_donations - donationStats.approved_donations - donationStats.completed_donations
                            ],
                            backgroundColor: [
                                'rgba(251, 191, 36, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(34, 197, 94, 0.8)',
                                'rgba(239, 68, 68, 0.8)'
                            ],
                            borderColor: [
                                'rgb(251, 191, 36)',
                                'rgb(59, 130, 246)',
                                'rgb(34, 197, 94)',
                                'rgb(239, 68, 68)'
                            ],
                            borderWidth: 2,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Estado Actual de las Donaciones',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                color: '#374151',
                                padding: 20
                            },
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    color: '#6B7280'
                                }
                            }
                        }
                    }
                });

                // Gráfico de Actividades por Categoría
                const activityCategories = @json($activitiesByCategory ?? []);
                const activitiesCategoryCtx = document.getElementById('activitiesCategoryChart').getContext('2d');
                new Chart(activitiesCategoryCtx, {
                    type: 'polarArea',
                    data: {
                        labels: Object.keys(activityCategories),
                        datasets: [{
                            data: Object.values(activityCategories),
                            backgroundColor: [
                                'rgba(99, 102, 241, 0.8)',
                                'rgba(168, 85, 247, 0.8)',
                                'rgba(236, 72, 153, 0.8)',
                                'rgba(34, 197, 94, 0.8)',
                                'rgba(59, 130, 246, 0.8)'
                            ],
                            borderColor: [
                                'rgb(99, 102, 241)',
                                'rgb(168, 85, 247)',
                                'rgb(236, 72, 153)',
                                'rgb(34, 197, 94)',
                                'rgb(59, 130, 246)'
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Actividades por Categoría',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                color: '#374151',
                                padding: 20
                            },
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    color: '#6B7280'
                                }
                            }
                        },
                        scales: {
                            r: {
                                ticks: {
                                    color: '#6B7280',
                                    backdropColor: 'transparent'
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            }
                        }
                    }
                });
            });


        </script>
    </div>
</x-app-layout>
