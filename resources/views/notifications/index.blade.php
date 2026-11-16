<x-app-layout>
    <x-page-header title="Notificaciones" subtitle="Mantente al día con todas tus actividades">
        <form method="POST" action="{{ route('api.notifications.mark-all-read') }}" class="inline">
            @csrf
            <x-action-button type="submit" variant="primary">
                Marcar todas como leídas
            </x-action-button>
        </form>
    </x-page-header>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-content-card>
                    @if($notifications->count() > 0)
                        <div class="space-y-4">
                            @foreach($notifications as $notification)
                                <div class="border rounded-lg p-4 {{ $notification->read_at ? 'bg-gray-50' : 'bg-blue-50 border-blue-200' }} hover:shadow-md transition-shadow duration-200">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-2">
                                                @php
                                                    $iconClass = match($notification->parsed_data['type']) {
                                                        'activity' => 'text-green-600',
                                                        'certificate' => 'text-blue-600',
                                                        'donation' => 'text-yellow-600',
                                                        'participation' => 'text-purple-600',
                                                        default => 'text-gray-600'
                                                    };
                                                @endphp
                                                
                                                <svg class="h-5 w-5 {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if($notification->parsed_data['type'] === 'activity')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6"></path>
                                                    @elseif($notification->parsed_data['type'] === 'certificate')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    @elseif($notification->parsed_data['type'] === 'donation')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h4l4 4v-4h4a2 2 0 002-2z"></path>
                                                    @endif
                                                </svg>
                                                
                                                <h3 class="font-semibold text-gray-900">{{ $notification->parsed_data['title'] }}</h3>
                                                
                                                @if(!$notification->read_at)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Nueva
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <p class="text-gray-700 mb-2">{{ $notification->parsed_data['message'] }}</p>
                                            
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                                </span>
                                                
                                                @if(!$notification->read_at)
                                                    <form method="POST" action="{{ route('api.notifications.read', $notification->id) }}" class="inline">
                                                        @csrf
                                                        <x-action-button type="submit" variant="info" size="sm">
                                                            Marcar como leída
                                                        </x-action-button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h4l4 4v-4h4a2 2 0 002-2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay notificaciones</h3>
                            <p class="mt-1 text-sm text-gray-500">No tienes notificaciones en este momento.</p>
                        </div>
                    @endif
            </x-content-card>
        </div>
    </div>
</x-app-layout>