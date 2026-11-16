@if($donationsList->count() > 0)
    <div class="space-y-4">
        @foreach($donationsList as $donation)
            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="flex-shrink-0">
                                @if($donation->user->profile_photo_path)
                                    <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $donation->user->profile_photo_path) }}" alt="">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-600 flex items-center justify-center">
                                        <span class="text-white font-medium">{{ substr($donation->user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900">{{ $donation->title }}</h4>
                                <p class="text-sm text-gray-600">Por: {{ $donation->user->name }} ({{ $donation->user->email }})</p>
                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($donation->description, 100) }}</p>
                            </div>
                            
                            <div class="flex flex-col items-end space-y-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $donation->getStatusBadgeColor() }}">
                                    @switch($donation->validation_status)
                                        @case('pending') ⏳ Pendiente @break
                                        @case('verifying') 🔍 Verificando @break
                                        @case('approved') ✅ Aprobada @break
                                        @case('rejected') ❌ Rechazada @break
                                    @endswitch
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $donation->getCategory() }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Monto:</span>
                                <div class="font-semibold text-lg text-green-600">Bs. {{ number_format($donation->amount, 2) }}</div>
                            </div>
                            <div>
                                <span class="text-gray-500">Puntos:</span>
                                <div class="font-semibold text-lg text-blue-600">{{ $donation->calculatePoints() }}</div>
                            </div>
                            <div>
                                <span class="text-gray-500">Método:</span>
                                <div class="font-medium">
                                    @if($donation->payment_method === 'bank_transfer')
                                        🏦 Transferencia
                                    @elseif($donation->payment_method === 'qr_code')
                                        📱 Código QR
                                    @else
                                        💳 Otro
                                    @endif
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-500">Fecha:</span>
                                <div class="font-medium">{{ $donation->donation_date->format('d/m/Y') }}</div>
                            </div>
                            <div>
                                <span class="text-gray-500">Código:</span>
                                <div class="font-mono text-xs">{{ $donation->verification_code ?? 'N/A' }}</div>
                            </div>
                        </div>
                        
                        @if($donation->verification_notes)
                            <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="text-xs text-blue-600 font-medium mb-1">Notas de verificación:</div>
                                <div class="text-sm text-blue-800">{{ $donation->verification_notes }}</div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex flex-col space-y-2 ml-4">
                        <button onclick="window.donationManager().showDonationDetails({{ $donation->id }})" 
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Ver Detalles
                        </button>
                        
                        @if($showActions && in_array($donation->validation_status, ['pending', 'verifying']))
                            <div class="flex space-x-2">
                                <form method="POST" action="{{ route('admin.donations.approve', $donation) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md">
                                        ✓ Aprobar
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.donations.reject', $donation) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="rejection_reason" value="Rechazado por administrador">
                                    <button type="submit" onclick="return confirm('¿Rechazar donación?')" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md">
                                        ✗ Rechazar
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-12">
        <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay donaciones en esta categoría</h3>
        <p class="text-gray-600">Las donaciones aparecerán aquí cuando cambien a este estado.</p>
    </div>
@endif