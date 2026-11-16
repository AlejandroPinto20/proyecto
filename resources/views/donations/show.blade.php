<x-app-layout>
    <x-page-header title="Detalle de Donación" subtitle="Información completa y estado de verificación">
        <div class="flex items-center space-x-3">
            @if($donation->validation_status === 'pending')
                <x-action-button href="{{ route('donations.edit', $donation) }}" variant="success">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </x-action-button>
            @endif
            <x-action-button href="{{ route('donations.index') }}" variant="secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </x-action-button>
        </div>
    </x-page-header>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Encabezado de la Donación -->
            <x-content-card>
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $donation->title }}</h1>
                        <p class="text-gray-600 mb-4">{{ $donation->description ?: 'Sin descripción adicional' }}</p>
                        
                        <div class="flex items-center space-x-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $donation->getStatusBadgeColor() }}">
                                @switch($donation->validation_status)
                                    @case('pending') ⏳ Pendiente de Verificación @break
                                    @case('verifying') 🔍 En Verificación @break
                                    @case('approved') ✅ Verificada y Aprobada @break
                                    @case('rejected') ❌ Rechazada @break
                                @endswitch
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                {{ $donation->getCategory() }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <div class="text-3xl font-bold text-green-600">Bs. {{ number_format($donation->amount, 2) }}</div>
                        <div class="text-sm text-gray-500">Monto de la donación</div>
                        <div class="mt-2 text-lg font-semibold text-blue-600">{{ $donation->calculatePoints() }} puntos</div>
                    </div>
                </div>
            </x-content-card>

            <!-- Información de Verificación -->
            <x-content-card header="Estado de Verificación">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Información de Validación</h4>
                        <div class="space-y-3">
                            @if($donation->verification_code)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm text-gray-600">Código de Verificación:</span>
                                    <span class="font-mono font-semibold text-gray-900">{{ $donation->verification_code }}</span>
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Estado de Validación:</span>
                                <span class="font-medium">{{ ucfirst($donation->validation_status) }}</span>
                            </div>
                            
                            @if($donation->verified_at)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Verificado el:</span>
                                    <span class="font-medium">{{ $donation->verified_at->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                            
                            @if($donation->verifiedBy)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Verificado por:</span>
                                    <span class="font-medium">{{ $donation->verifiedBy->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Detalles de Pago</h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Método de Pago:</span>
                                <span class="font-medium">
                                    @if($donation->payment_method === 'bank_transfer')
                                        🏦 Transferencia Bancaria
                                    @elseif($donation->payment_method === 'qr_code')
                                        📱 Código QR
                                    @else
                                        💳 Otro
                                    @endif
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Fecha de Donación:</span>
                                <span class="font-medium">{{ $donation->donation_date->format('d/m/Y') }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Registrado el:</span>
                                <span class="font-medium">{{ $donation->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($donation->verification_notes)
                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h5 class="font-medium text-blue-900 mb-2">Notas de Verificación:</h5>
                        <p class="text-blue-800">{{ $donation->verification_notes }}</p>
                    </div>
                @endif
            </x-content-card>

            <!-- Información de Pago -->
            @if($donation->bank_account_info || $donation->qr_code_data)
                <x-content-card header="Información de Pago">
                    @if($donation->bank_account_info)
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 mb-3">Información Bancaria</h4>
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-blue-800">{{ $donation->bank_account_info }}</p>
                            </div>
                        </div>
                    @endif

                    @if($donation->qr_code_data)
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Código QR de Donación</h4>
                            <div class="bg-gray-50 p-6 rounded-lg text-center">
                                <div id="qrCodeDisplay" class="inline-block mb-4"></div>
                                <div class="text-sm text-gray-600">
                                    @php
                                        $qrData = json_decode($donation->qr_code_data, true);
                                    @endphp
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <span class="font-medium">Monto:</span>
                                            <div>Bs. {{ number_format($qrData['amount'] ?? 0, 2) }}</div>
                                        </div>
                                        <div>
                                            <span class="font-medium">Referencia:</span>
                                            <div class="font-mono text-xs">{{ $qrData['reference'] ?? 'N/A' }}</div>
                                        </div>
                                        <div>
                                            <span class="font-medium">Destinatario:</span>
                                            <div>{{ $qrData['recipient'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const qrData = @json($donation->qr_code_data);
                                const qrContainer = document.getElementById('qrCodeDisplay');

                                new QRCode(qrContainer, {
                                    text: qrData,
                                    width: 200,
                                    height: 200,
                                    colorDark: "#000000",
                                    colorLight: "#ffffff",
                                    correctLevel: QRCode.CorrectLevel.H
                                });
                            });
                        </script>
                    @endif
                </x-content-card>
            @endif

            @if($donation->notes)
                <x-content-card header="Notas Adicionales">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-700">{{ $donation->notes }}</p>
                    </div>
                </x-content-card>
            @endif

            <!-- Proceso de Verificación -->
            <x-content-card header="Proceso de Verificación">
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">1. Donación Registrada</h4>
                            <p class="text-sm text-gray-600">Tu donación ha sido registrada exitosamente en el sistema.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 {{ $donation->validation_status !== 'pending' ? 'bg-green-500' : 'bg-yellow-500' }} rounded-full flex items-center justify-center">
                                @if($donation->validation_status !== 'pending')
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">2. Verificación de Comprobantes</h4>
                            <p class="text-sm text-gray-600">
                                @if($donation->validation_status === 'pending')
                                    Esperando verificación por parte de los administradores.
                                @else
                                    Verificación completada el {{ $donation->verified_at?->format('d/m/Y H:i') }}.
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 {{ $donation->validation_status === 'approved' ? 'bg-green-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center">
                                @if($donation->validation_status === 'approved')
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @else
                                    <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">3. Puntos Otorgados</h4>
                            <p class="text-sm text-gray-600">
                                @if($donation->validation_status === 'approved')
                                    ¡Felicidades! Has ganado {{ $donation->calculatePoints() }} puntos.
                                @else
                                    Los puntos se otorgarán una vez aprobada la donación.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </x-content-card>

            @if($donation->validation_status === 'pending')
                <div class="flex justify-end">
                    <form method="POST" action="{{ route('donations.destroy', $donation) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <x-action-button type="submit" variant="danger" onclick="return confirm('¿Estás seguro de que quieres eliminar esta donación?')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Eliminar Donación
                        </x-action-button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
