<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center">
                    <svg class="h-8 w-8 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    {{ __('Panel Administrativo - UNIFRANZ') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1 ml-11">Gestión y análisis en tiempo real</p>
            </div>
            <div class="text-right bg-white px-4 py-2 rounded-lg shadow-sm">
                <div class="text-sm font-semibold text-gray-700">{{ now()->format('d/m/Y') }}</div>
                <div class="text-xs text-gray-500" id="current-time">{{ now()->format('H:i:s') }}</div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-blue-400 via-purple-400 to-orange-400 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Estadísticas principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-orange-500 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Total Usuarios</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                            </div>
                            <div class="p-3 bg-orange-100 rounded-full">
                                <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-green-500 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Actividades</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_activities'] }}</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-blue-500 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Certificados</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_certificates'] }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-purple-500 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Total Donaciones</p>
                                <p class="text-2xl font-bold text-gray-900">Bs. {{ number_format($stats['total_donations_amount'], 2) }}</p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-yellow-500 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Solicitudes Pendientes</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['pending_participations'] }}</p>
                            </div>
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accesos rápidos -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl mb-6 border-2 border-orange-200">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                        <div class="p-2 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl mr-3 shadow-lg">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        Accesos Rápidos
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        <a href="{{ route('admin.users') }}" class="group relative bg-gradient-to-br from-white to-orange-50 hover:from-orange-500 hover:to-orange-600 border-2 border-orange-300 p-6 rounded-2xl text-center transition-all duration-500 transform hover:scale-105 shadow-lg hover:shadow-2xl hover:-translate-y-3 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-orange-400 to-orange-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative z-10">
                                <div class="p-3 bg-gradient-to-br from-orange-400 to-orange-600 group-hover:from-white group-hover:to-orange-50 rounded-xl inline-block mb-3 shadow-lg transition-all duration-500 group-hover:rotate-12 group-hover:scale-110">
                                    <svg class="h-8 w-8 text-white group-hover:text-orange-600 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800 group-hover:text-white transition-colors duration-500">Usuarios</p>
                            </div>
                        </a>
                        <a href="{{ route('admin.activities') }}" class="group relative bg-gradient-to-br from-white to-green-50 hover:from-green-500 hover:to-green-600 border-2 border-green-300 p-6 rounded-2xl text-center transition-all duration-500 transform hover:scale-105 shadow-lg hover:shadow-2xl hover:-translate-y-3 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-green-400 to-green-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative z-10">
                                <div class="p-3 bg-gradient-to-br from-green-400 to-green-600 group-hover:from-white group-hover:to-green-50 rounded-xl inline-block mb-3 shadow-lg transition-all duration-500 group-hover:rotate-12 group-hover:scale-110">
                                    <svg class="h-8 w-8 text-white group-hover:text-green-600 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800 group-hover:text-white transition-colors duration-500">Actividades</p>
                            </div>
                        </a>
                        <a href="{{ route('admin.activities.pending-participations') }}" class="group relative bg-gradient-to-br from-white to-yellow-50 hover:from-yellow-500 hover:to-yellow-600 border-2 border-yellow-300 p-6 rounded-2xl text-center transition-all duration-500 transform hover:scale-105 shadow-lg hover:shadow-2xl hover:-translate-y-3 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-yellow-400 to-yellow-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative z-10">
                                <div class="p-3 bg-gradient-to-br from-yellow-400 to-yellow-600 group-hover:from-white group-hover:to-yellow-50 rounded-xl inline-block mb-3 shadow-lg transition-all duration-500 group-hover:rotate-12 group-hover:scale-110">
                                    <svg class="h-8 w-8 text-white group-hover:text-yellow-600 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800 group-hover:text-white transition-colors duration-500">Solicitudes</p>
                            </div>
                        </a>
                        <a href="{{ route('admin.certificates') }}" class="group relative bg-gradient-to-br from-white to-blue-50 hover:from-blue-500 hover:to-blue-600 border-2 border-blue-300 p-6 rounded-2xl text-center transition-all duration-500 transform hover:scale-105 shadow-lg hover:shadow-2xl hover:-translate-y-3 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative z-10">
                                <div class="p-3 bg-gradient-to-br from-blue-400 to-blue-600 group-hover:from-white group-hover:to-blue-50 rounded-xl inline-block mb-3 shadow-lg transition-all duration-500 group-hover:rotate-12 group-hover:scale-110">
                                    <svg class="h-8 w-8 text-white group-hover:text-blue-600 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800 group-hover:text-white transition-colors duration-500">Certificados</p>
                            </div>
                        </a>
                        <a href="{{ route('admin.donations') }}" class="group relative bg-gradient-to-br from-white to-purple-50 hover:from-purple-500 hover:to-purple-600 border-2 border-purple-300 p-6 rounded-2xl text-center transition-all duration-500 transform hover:scale-105 shadow-lg hover:shadow-2xl hover:-translate-y-3 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-purple-400 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative z-10">
                                <div class="p-3 bg-gradient-to-br from-purple-400 to-purple-600 group-hover:from-white group-hover:to-purple-50 rounded-xl inline-block mb-3 shadow-lg transition-all duration-500 group-hover:rotate-12 group-hover:scale-110">
                                    <svg class="h-8 w-8 text-white group-hover:text-purple-600 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800 group-hover:text-white transition-colors duration-500">Donaciones</p>
                            </div>
                        </a>
                        <a href="{{ route('admin.reports') }}" class="group relative bg-gradient-to-br from-white to-gray-50 hover:from-gray-600 hover:to-gray-700 border-2 border-gray-300 p-6 rounded-2xl text-center transition-all duration-500 transform hover:scale-105 shadow-lg hover:shadow-2xl hover:-translate-y-3 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-gray-500 to-gray-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative z-10">
                                <div class="p-3 bg-gradient-to-br from-gray-500 to-gray-700 group-hover:from-white group-hover:to-gray-50 rounded-xl inline-block mb-3 shadow-lg transition-all duration-500 group-hover:rotate-12 group-hover:scale-110">
                                    <svg class="h-8 w-8 text-white group-hover:text-gray-700 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800 group-hover:text-white transition-colors duration-500">Reportes</p>
                            </div>
                        </a>
                        <a href="{{ route('admin.skills.index') }}" class="group relative bg-gradient-to-br from-white to-indigo-50 hover:from-indigo-600 hover:to-blue-600 border-2 border-indigo-300 p-6 rounded-2xl text-center transition-all duration-500 transform hover:scale-105 shadow-lg hover:shadow-2xl hover:-translate-y-3 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative z-10">
                                <div class="p-3 bg-gradient-to-br from-indigo-500 to-blue-600 group-hover:from-white group-hover:to-indigo-50 rounded-xl inline-block mb-3 shadow-lg transition-all duration-500 group-hover:rotate-12 group-hover:scale-110">
                                    <svg class="h-8 w-8 text-white group-hover:text-indigo-600 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800 group-hover:text-white transition-colors duration-500">Habilidades</p>
                            </div>
                        </a>
                        <a href="{{ route('admin.markov.index') }}" class="group relative bg-gradient-to-br from-white to-pink-50 hover:from-purple-600 hover:to-pink-600 border-2 border-pink-300 p-6 rounded-2xl text-center transition-all duration-500 transform hover:scale-105 shadow-lg hover:shadow-2xl hover:-translate-y-3 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative z-10">
                                <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-600 group-hover:from-white group-hover:to-pink-50 rounded-xl inline-block mb-3 shadow-lg transition-all duration-500 group-hover:rotate-12 group-hover:scale-110">
                                    <svg class="h-8 w-8 text-white group-hover:text-pink-600 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800 group-hover:text-white transition-colors duration-500">Markov</p>
                            </div>
                        </a>
                        <a href="{{ route('admin.notification-logs') }}" class="group relative bg-gradient-to-br from-white to-teal-50 hover:from-teal-600 hover:to-cyan-600 border-2 border-teal-300 p-6 rounded-2xl text-center transition-all duration-500 transform hover:scale-105 shadow-lg hover:shadow-2xl hover:-translate-y-3 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-teal-500 to-cyan-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative z-10">
                                <div class="p-3 bg-gradient-to-br from-teal-500 to-cyan-600 group-hover:from-white group-hover:to-teal-50 rounded-xl inline-block mb-3 shadow-lg transition-all duration-500 group-hover:rotate-12 group-hover:scale-110">
                                    <svg class="h-8 w-8 text-white group-hover:text-teal-600 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h4l4 4v-4h4a2 2 0 002-2z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800 group-hover:text-white transition-colors duration-500">Notificaciones</p>
                            </div>
                        </a>
                        <a href="{{ route('admin.access-logs') }}" class="group relative bg-gradient-to-br from-white to-red-50 hover:from-red-600 hover:to-pink-600 border-2 border-red-300 p-6 rounded-2xl text-center transition-all duration-500 transform hover:scale-105 shadow-lg hover:shadow-2xl hover:-translate-y-3 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-red-500 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative z-10">
                                <div class="p-3 bg-gradient-to-br from-red-500 to-pink-600 group-hover:from-white group-hover:to-red-50 rounded-xl inline-block mb-3 shadow-lg transition-all duration-500 group-hover:rotate-12 group-hover:scale-110">
                                    <svg class="h-8 w-8 text-white group-hover:text-red-600 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.5-1.5a2.5 2.5 0 00-5 0l-.5.5a2.5 2.5 0 105 0l-.5-.5z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800 group-hover:text-white transition-colors duration-500">Accesos</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Usuarios recientes y actividades -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Usuarios recientes -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-5 flex items-center">
                            <svg class="h-6 w-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Usuarios Recientes
                        </h3>
                        <div class="space-y-3">
                            @forelse($recentUsers as $user)
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-xl hover:shadow-md transition-all duration-200">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-md">
                                            <span class="text-white font-bold text-lg">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $user->role === 'admin' ? 'bg-orange-100 text-orange-700 border border-orange-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No hay usuarios recientes.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Actividades recientes -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-5 flex items-center">
                            <svg class="h-6 w-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Actividades Recientes
                        </h3>
                        <div class="space-y-3">
                            @forelse($recentActivities as $activity)
                            <div class="p-4 bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-xl hover:shadow-md transition-all duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900 mb-1">{{ $activity->name }}</h4>
                                        <p class="text-xs text-gray-500 flex items-center">
                                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            {{ $activity->user->name }}
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ml-2
                                        {{ $activity->status === 'active' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-yellow-100 text-yellow-700 border border-yellow-200' }}">
                                        {{ ucfirst($activity->status) }}
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No hay actividades recientes.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top voluntarios -->
            <div class="mt-6 bg-white overflow-hidden shadow-lg sm:rounded-xl">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="h-6 w-6 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                        </svg>
                        Top Voluntarios
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        @forelse($topVolunteers as $index => $ranking)
                        <div class="text-center p-5 bg-gradient-to-br from-gray-50 to-white border-2 {{ $index === 0 ? 'border-yellow-400' : ($index === 1 ? 'border-gray-300' : ($index === 2 ? 'border-orange-300' : 'border-gray-200')) }} rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 relative">
                            @if($index < 3)
                            <div class="absolute -top-3 -right-3 w-8 h-8 rounded-full {{ $index === 0 ? 'bg-yellow-400' : ($index === 1 ? 'bg-gray-400' : 'bg-orange-400') }} flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-sm">{{ $index + 1 }}</span>
                            </div>
                            @endif
                            <div class="flex-shrink-0 h-16 w-16 mx-auto mb-3">
                                <div class="h-16 w-16 rounded-full bg-gradient-to-br {{ $index === 0 ? 'from-yellow-400 to-yellow-600' : ($index === 1 ? 'from-gray-400 to-gray-600' : ($index === 2 ? 'from-orange-400 to-orange-600' : 'from-blue-400 to-blue-600')) }} flex items-center justify-center shadow-md">
                                    <span class="text-white font-bold text-xl">{{ substr($ranking->user->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="text-sm font-bold text-gray-900 truncate px-2">{{ $ranking->user->name }}</div>
                            <div class="text-lg font-bold text-orange-600 mt-1">{{ $ranking->total_points }}</div>
                            <div class="text-xs text-gray-500">puntos</div>
                            <div class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                {{ $ranking->rank }}
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                            </svg>
                            <p class="mt-4 text-sm text-gray-500">No hay rankings disponibles.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Actualizar reloj
        setInterval(() => {
            const now = new Date();
            document.getElementById('current-time').textContent = now.toLocaleTimeString('es-BO');
        }, 1000);
    </script>
</x-app-layout>
