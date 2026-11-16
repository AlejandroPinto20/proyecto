<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">{{ $activity->title }}</h1>
                <p class="text-gray-600 mt-2">Gestionar participantes y marcar completados</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Participantes ({{ $participants->count() }})</h3>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @forelse($participants as $participant)
                        <div class="p-4 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($participant->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $participant->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $participant->email }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    {{ $participant->pivot->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($participant->pivot->status === 'joined' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($participant->pivot->status) }}
                                </span>
                                
                                @if($participant->pivot->status !== 'completed')
                                    <button onclick="markCompleted({{ $participant->id }})" 
                                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                        Marcar Completado
                                    </button>
                                @else
                                    <span class="text-green-600 text-sm">✓ Completado</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <p class="text-gray-500">No hay participantes registrados</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function markCompleted(userId) {
            if (!confirm('¿Marcar como completado?')) return;
            
            fetch(`/admin/activities/{{ $activity->id }}/users/${userId}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }
    </script>
</x-app-layout>