<x-app-layout>
    <x-page-header 
        title="Solicitudes de Participación"
        subtitle="Gestiona las solicitudes pendientes de participación en actividades">
        <x-slot name="actions">
            <x-action-button href="{{ route('admin.activities') }}" variant="secondary">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                </svg>
                Volver a Actividades
            </x-action-button>
        </x-slot>
    </x-page-header>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-content-card>
                @if($pendingParticipations->count() > 0)
                    <!-- Stats Card -->
                    <div class="mb-8">
                        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg p-6 text-white">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-yellow-100">Solicitudes Pendientes</p>
                                    <p class="text-2xl font-bold">{{ $pendingParticipations->sum(function($activity) { return $activity->users->where('pivot.status', 'pending')->count(); }) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @foreach($pendingParticipations as $activity)
                            <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg p-6 border border-gray-200">
                                <div class="flex items-center mb-6">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-xl font-semibold text-gray-900">{{ $activity->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $activity->users->where('pivot.status', 'pending')->count() }} solicitud{{ $activity->users->where('pivot.status', 'pending')->count() != 1 ? 'es' : '' }} pendiente{{ $activity->users->where('pivot.status', 'pending')->count() != 1 ? 's' : '' }}</p>
                                    </div>
                                </div>
                                
                                <div class="grid gap-4">
                                    @foreach($activity->users->where('pivot.status', 'pending') as $user)
                                        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-4">
                                                    <div class="flex-shrink-0 h-12 w-12">
                                                        <div class="h-12 w-12 rounded-full bg-gradient-to-r from-orange-400 to-pink-500 flex items-center justify-center border-2 border-white shadow-lg">
                                                            <span class="text-white font-bold text-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                                        <div class="flex items-center mt-1">
                                                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <p class="text-xs text-gray-500">Solicitó: {{ \Carbon\Carbon::parse($user->pivot->joined_at)->format('d/m/Y H:i') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex space-x-2">
                                                    <x-action-button 
                                                        onclick="approveParticipation({{ $activity->id }}, {{ $user->id }})" 
                                                        variant="success" 
                                                        size="sm">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Aprobar
                                                    </x-action-button>
                                                    <x-action-button 
                                                        onclick="rejectParticipation({{ $activity->id }}, {{ $user->id }})" 
                                                        variant="danger" 
                                                        size="sm">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Rechazar
                                                    </x-action-button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No hay solicitudes pendientes</h3>
                        <p class="mt-1 text-gray-500">Todas las solicitudes de participación han sido procesadas.</p>
                        <div class="mt-6">
                            <x-action-button href="{{ route('admin.activities') }}" variant="primary">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                Ver Todas las Actividades
                            </x-action-button>
                        </div>
                    </div>
                @endif
            </x-content-card>
        </div>
    </div>

    <script>
        function approveParticipation(activityId, userId) {
            if (!confirm('¿Estás seguro de aprobar esta solicitud?')) return;
            
            fetch(`/admin/activities/${activityId}/users/${userId}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('Solicitud aprobada exitosamente', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showMessage(data.message || 'Error al aprobar la solicitud', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Error al procesar la solicitud', 'error');
            });
        }

        function rejectParticipation(activityId, userId) {
            if (!confirm('¿Estás seguro de rechazar esta solicitud?')) return;
            
            fetch(`/admin/activities/${activityId}/users/${userId}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('Solicitud rechazada', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showMessage(data.message || 'Error al rechazar la solicitud', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Error al procesar la solicitud', 'error');
            });
        }

        function showMessage(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 shadow-lg ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
</x-app-layout>