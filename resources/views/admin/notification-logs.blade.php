<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Page Header -->
            <x-page-header 
                title="Logs de Notificaciones" 
                subtitle="Historial completo de notificaciones enviadas en el sistema">
                <x-slot name="actions">
                    <x-action-button variant="secondary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Exportar Logs
                    </x-action-button>
                </x-slot>
            </x-page-header>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <x-content-card class="bg-gradient-to-br from-blue-100 to-indigo-100 border-blue-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h4l4 4v-4h4a2 2 0 002-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Notificaciones</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $notifications->total() ?? 0 }}</p>
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
                            <p class="text-sm font-medium text-gray-600">Notificaciones Leídas</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $notifications->where('is_read', true)->count() ?? 0 }}</p>
                        </div>
                    </div>
                </x-content-card>

                <x-content-card class="bg-gradient-to-br from-yellow-100 to-orange-100 border-yellow-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pendientes</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $notifications->where('is_read', false)->count() ?? 0 }}</p>
                        </div>
                    </div>
                </x-content-card>

                <x-content-card class="bg-gradient-to-br from-purple-100 to-pink-100 border-purple-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Hoy</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $notifications->where('created_at', '>=', now()->startOfDay())->count() ?? 0 }}</p>
                        </div>
                    </div>
                </x-content-card>
            </div>

            <!-- Notifications Table -->
            <x-content-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-gray-900">Historial de Notificaciones</h3>
                </x-slot>
                
                @if($notifications->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mensaje</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($notifications as $notification)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-orange-500 to-red-500 flex items-center justify-center">
                                                        <span class="text-white font-medium text-sm">
                                                            {{ strtoupper(substr($notification->user_name ?? 'U', 0, 1)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $notification->user_name ?? 'Usuario' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $notification->user_email ?? 'email@unifranz.edu.bo' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if(str_contains($notification->type_name ?? '', 'Approved')) bg-green-100 text-green-800
                                                @elseif(str_contains($notification->type_name ?? '', 'Rejected')) bg-red-100 text-red-800
                                                @elseif(str_contains($notification->type_name ?? '', 'Request')) bg-yellow-100 text-yellow-800
                                                @elseif(str_contains($notification->type_name ?? '', 'Certificate')) bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $notification->type_name ?? 'General' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $notification->title ?? 'Notificación del sistema' }}</div>
                                            <div class="text-sm text-gray-500 truncate max-w-xs">
                                                {{ $notification->message ?? 'Mensaje de notificación' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($notification->is_read ?? false)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Leída
                                                </span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    No leída
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ \Carbon\Carbon::parse($notification->created_at ?? now())->format('d/m/Y H:i') }}</div>
                                            <div class="text-xs text-gray-400">
                                                {{ \Carbon\Carbon::parse($notification->created_at ?? now())->diffForHumans() }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($notifications->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h4l4 4v-4h4a2 2 0 002-2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay notificaciones</h3>
                        <p class="mt-1 text-sm text-gray-500">No se han enviado notificaciones en el sistema aún.</p>
                    </div>
                @endif
            </x-content-card>
        </div>
    </div>
</x-app-layout>