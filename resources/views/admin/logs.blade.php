<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Logs del Sistema</h1>
                <p class="text-gray-600 mt-2">Monitorea la actividad del sistema</p>
            </div>

            <!-- Tabs -->
            <div class="mb-8">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button onclick="showTab('activity')" id="tab-activity" class="tab-button active py-2 px-1 border-b-2 font-medium text-sm">
                            Actividad de Usuarios
                        </button>
                        <button onclick="showTab('system')" id="tab-system" class="tab-button py-2 px-1 border-b-2 font-medium text-sm">
                            Logs del Sistema
                        </button>
                        <button onclick="showTab('errors')" id="tab-errors" class="tab-button py-2 px-1 border-b-2 font-medium text-sm">
                            Errores
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Activity Logs -->
            <div id="content-activity" class="tab-content">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Actividad Reciente de Usuarios</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($activityLogs as $log)
                            <div class="p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-blue-100 rounded-lg">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $log->user->name ?? 'Usuario eliminado' }}</p>
                                            <p class="text-sm text-gray-600">{{ $log->action }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                                        <p class="text-xs text-gray-400">{{ $log->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <p class="text-gray-500">No hay logs de actividad</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- System Logs -->
            <div id="content-system" class="tab-content hidden">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Logs del Sistema</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($systemLogs as $log)
                            <div class="p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-green-100 rounded-lg">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $log->event }}</p>
                                            <p class="text-sm text-gray-600">{{ $log->description }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                                        <p class="text-xs text-gray-400">{{ $log->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <p class="text-gray-500">No hay logs del sistema</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Error Logs -->
            <div id="content-errors" class="tab-content hidden">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Logs de Errores</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($errorLogs as $log)
                            <div class="p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-red-100 rounded-lg">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $log->level }} - {{ $log->message }}</p>
                                            <p class="text-sm text-gray-600">{{ $log->context }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                                        <p class="text-xs text-gray-400">{{ $log->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <p class="text-gray-500">No hay errores registrados</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Ocultar todos los contenidos
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remover clase activa de todos los tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            
            // Mostrar contenido seleccionado
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Activar tab seleccionado
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
            activeTab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        }
        
        // Inicializar primer tab como activo
        document.addEventListener('DOMContentLoaded', function() {
            showTab('activity');
        });
    </script>
</x-app-layout>