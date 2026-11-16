<x-app-layout>
    <x-page-header title="Centro de Donaciones" subtitle="Contribuye al cambio social y gana puntos por tu generosidad">
        <x-slot name="actions">
            <a href="{{ route('donations.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nueva Donación
            </a>
        </x-slot>
    </x-page-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @if(!($canMakeDonation ?? true) && $pendingDonation)
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-6 py-4 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <h4 class="font-medium mb-1">Donación en Proceso</h4>
                            <p class="text-sm">
                                Tienes una donación <strong>"{{ $pendingDonation->title }}"</strong> 
                                @if($pendingDonation->validation_status === 'pending')
                                    pendiente de verificación
                                @else
                                    en proceso de verificación
                                @endif
                                . No puedes crear nuevas donaciones hasta que esta sea aprobada o rechazada.
                            </p>
                            <p class="text-xs mt-2 opacity-75">
                                Código de verificación: <span class="font-mono">{{ $pendingDonation->verification_code }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Estadísticas del Usuario -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @php
                    $userDonations = $donations->where('user_id', auth()->id());
                    $totalAmount = $userDonations->where('validation_status', 'approved')->sum('amount');
                    $totalPoints = $userDonations->where('validation_status', 'approved')->sum(function($d) { return $d->calculatePoints(); });
                    $pendingCount = $userDonations->where('validation_status', 'pending')->count();
                    $approvedCount = $userDonations->where('validation_status', 'approved')->count();
                @endphp
                
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-xl border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600">Total Donado</p>
                            <p class="text-2xl font-bold text-green-900">Bs. {{ number_format($totalAmount, 2) }}</p>
                        </div>
                        <div class="p-3 bg-green-500 rounded-full">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-600">Puntos Ganados</p>
                            <p class="text-2xl font-bold text-blue-900">{{ number_format($totalPoints) }}</p>
                        </div>
                        <div class="p-3 bg-blue-500 rounded-full">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-violet-50 p-6 rounded-xl border border-purple-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600">Donaciones Aprobadas</p>
                            <p class="text-2xl font-bold text-purple-900">{{ $approvedCount }}</p>
                        </div>
                        <div class="p-3 bg-purple-500 rounded-full">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-amber-50 p-6 rounded-xl border border-yellow-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-600">En Revisión</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $pendingCount }}</p>
                        </div>
                        <div class="p-3 bg-yellow-500 rounded-full">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sistema de Puntos por Donación -->
            <div class="bg-gradient-to-r from-indigo-50 via-purple-50 to-pink-50 p-6 rounded-xl border border-indigo-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Sistema de Puntos por Donación
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="text-center p-3 bg-white rounded-lg border border-gray-200">
                        <div class="text-2xl mb-1">💵</div>
                        <div class="text-xs font-medium text-gray-600">Micro</div>
                        <div class="text-sm font-bold text-gray-900">1-50 Bs</div>
                        <div class="text-xs text-green-600 font-medium">10 puntos</div>
                    </div>
                    <div class="text-center p-3 bg-white rounded-lg border border-gray-200">
                        <div class="text-2xl mb-1">💰</div>
                        <div class="text-xs font-medium text-gray-600">Pequeña</div>
                        <div class="text-sm font-bold text-gray-900">51-200 Bs</div>
                        <div class="text-xs text-green-600 font-medium">25 puntos</div>
                    </div>
                    <div class="text-center p-3 bg-white rounded-lg border border-gray-200">
                        <div class="text-2xl mb-1">🏦</div>
                        <div class="text-xs font-medium text-gray-600">Mediana</div>
                        <div class="text-sm font-bold text-gray-900">201-500 Bs</div>
                        <div class="text-xs text-green-600 font-medium">50 puntos</div>
                    </div>
                    <div class="text-center p-3 bg-white rounded-lg border border-gray-200">
                        <div class="text-2xl mb-1">🏢</div>
                        <div class="text-xs font-medium text-gray-600">Grande</div>
                        <div class="text-sm font-bold text-gray-900">501-1000 Bs</div>
                        <div class="text-xs text-green-600 font-medium">100 puntos</div>
                    </div>
                    <div class="text-center p-3 bg-white rounded-lg border border-gray-200">
                        <div class="text-2xl mb-1">🏤</div>
                        <div class="text-xs font-medium text-gray-600">Corporativa</div>
                        <div class="text-sm font-bold text-gray-900">1001+ Bs</div>
                        <div class="text-xs text-green-600 font-medium">200 puntos</div>
                    </div>
                </div>
            </div>

            <!-- Lista de Donaciones Mejorada -->
            <x-content-card header="Historial de Donaciones">
                @if($donations->count() > 0)
                    <div class="space-y-4">
                        @foreach($donations as $donation)
                            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $donation->title }}</h3>
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
                                        
                                        <p class="text-gray-600 text-sm mb-3">{{ Str::limit($donation->description, 120) }}</p>
                                        
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
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
                                        </div>
                                        
                                        @if($donation->verification_code)
                                            <div class="mt-3 p-2 bg-gray-50 rounded text-xs">
                                                <span class="text-gray-500">Código de verificación:</span>
                                                <span class="font-mono font-medium">{{ $donation->verification_code }}</span>
                                            </div>
                                        @endif
                                        
                                        @if($donation->verification_notes)
                                            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                                <div class="text-xs text-blue-600 font-medium mb-1">Notas de verificación:</div>
                                                <div class="text-sm text-blue-800">{{ $donation->verification_notes }}</div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex flex-col space-y-2 ml-4">
                                        <x-action-button href="{{ route('donations.show', $donation) }}" variant="info" size="sm">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Ver
                                        </x-action-button>
                                        
                                        @if($donation->validation_status === 'pending')
                                            <x-action-button href="{{ route('donations.edit', $donation) }}" variant="success" size="sm">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Editar
                                            </x-action-button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        {{ $donations->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">¡Comienza a donar hoy!</h3>
                        <p class="text-gray-600 mb-6">Aún no has realizado ninguna donación. Contribuye al cambio social y gana puntos.</p>
                        <a href="{{ route('donations.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Hacer mi primera donación
                        </a>
                    </div>
                @endif
            </x-content-card>
        </div>
    </div>

    <style>
        @keyframes pulse-gentle {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        .animate-pulse-gentle {
            animation: pulse-gentle 2s ease-in-out infinite;
        }
    </style>
</x-app-layout>
