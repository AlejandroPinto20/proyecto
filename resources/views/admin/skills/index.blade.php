<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Page Header -->
            <x-page-header 
                title="Gestión de Habilidades" 
                subtitle="Administra las habilidades disponibles para el sistema de recomendaciones">
                <x-slot name="actions">
                    <x-action-button variant="primary" href="{{ route('admin.skills.create') }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nueva Habilidad
                    </x-action-button>
                </x-slot>
            </x-page-header>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <x-content-card class="bg-gradient-to-br from-purple-100 to-indigo-100 border-purple-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Habilidades</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $skills->total() }}</p>
                        </div>
                    </div>
                </x-content-card>

                <x-content-card class="bg-gradient-to-br from-green-100 to-teal-100 border-green-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-r from-green-500 to-teal-500 rounded-full">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Habilidades Activas</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $skills->where('is_active', true)->count() }}</p>
                        </div>
                    </div>
                </x-content-card>

                <x-content-card class="bg-gradient-to-br from-blue-100 to-cyan-100 border-blue-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Usuarios con Habilidades</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $skills->sum(function($skill) { return $skill->users()->count(); }) }}</p>
                        </div>
                    </div>
                </x-content-card>

                <x-content-card class="bg-gradient-to-br from-orange-100 to-red-100 border-orange-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-r from-orange-500 to-red-500 rounded-full">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Actividades Vinculadas</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $skills->sum(function($skill) { return $skill->activities()->count(); }) }}</p>
                        </div>
                    </div>
                </x-content-card>
            </div>

            <!-- Skills Table -->
            <x-content-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-gray-900">Lista de Habilidades</h3>
                </x-slot>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Habilidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuarios</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actividades</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($skills as $skill)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="text-2xl mr-3">{{ $skill->getEmoji() }}</span>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $skill->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $skill->slug }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 capitalize">
                                            {{ $skill->category }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $skill->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $skill->is_active ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $skill->users()->count() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            {{ $skill->activities()->count() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <x-action-button variant="info" size="sm" href="{{ route('admin.skills.show', $skill) }}">
                                            Ver
                                        </x-action-button>
                                        <x-action-button variant="secondary" size="sm" href="{{ route('admin.skills.edit', $skill) }}">
                                            Editar
                                        </x-action-button>
                                        <form method="POST" action="{{ route('admin.skills.destroy', $skill) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <x-action-button variant="danger" size="sm" type="submit" onclick="return confirm('¿Estás seguro de eliminar esta habilidad?')">
                                                Eliminar
                                            </x-action-button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay habilidades</h3>
                                        <p class="mt-1 text-sm text-gray-500">Comienza creando una nueva habilidad para el sistema.</p>
                                        <div class="mt-6">
                                            <x-action-button variant="primary" href="{{ route('admin.skills.create') }}">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Nueva Habilidad
                                            </x-action-button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($skills->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $skills->links() }}
                    </div>
                @endif
            </x-content-card>
        </div>
    </div>
</x-app-layout>
