<x-app-layout>
    <x-page-header 
        title="Gestión de Donaciones"
        subtitle="Administra y gestiona las donaciones del sistema">
        <div class="flex items-center space-x-3">
            <div class="text-sm text-gray-500">
                Última actualización: {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>
    </x-page-header>

    <div class="py-8" x-data="donationManager()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @php
                    $pendingCount = $donations->where('validation_status', 'pending')->count();
                    $verifyingCount = $donations->where('validation_status', 'verifying')->count();
                    $approvedCount = $donations->where('validation_status', 'approved')->count();
                    $rejectedCount = $donations->where('validation_status', 'rejected')->count();
                    $totalAmount = $donations->where('validation_status', 'approved')->sum('amount');
                @endphp
                
                <div class="bg-gradient-to-br from-yellow-50 to-amber-50 p-4 rounded-xl border border-yellow-200 cursor-pointer hover:shadow-md transition-shadow" @click="activeTab = 'pending'" :class="{'ring-2 ring-yellow-400': activeTab === 'pending'}">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-600">Pendientes</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $pendingCount }}</p>
                        </div>
                        <div class="p-2 bg-yellow-500 rounded-full">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-xl border border-blue-200 cursor-pointer hover:shadow-md transition-shadow" @click="activeTab = 'verifying'" :class="{'ring-2 ring-blue-400': activeTab === 'verifying'}">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-600">Verificando</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $verifyingCount }}</p>
                        </div>
                        <div class="p-2 bg-blue-500 rounded-full">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-4 rounded-xl border border-green-200 cursor-pointer hover:shadow-md transition-shadow" @click="activeTab = 'approved'" :class="{'ring-2 ring-green-400': activeTab === 'approved'}">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600">Aprobadas</p>
                            <p class="text-2xl font-bold text-green-900">{{ $approvedCount }}</p>
                        </div>
                        <div class="p-2 bg-green-500 rounded-full">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-50 to-pink-50 p-4 rounded-xl border border-red-200 cursor-pointer hover:shadow-md transition-shadow" @click="activeTab = 'rejected'" :class="{'ring-2 ring-red-400': activeTab === 'rejected'}">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-red-600">Rechazadas</p>
                            <p class="text-2xl font-bold text-red-900">{{ $rejectedCount }}</p>
                        </div>
                        <div class="p-2 bg-red-500 rounded-full">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-violet-50 p-4 rounded-xl border border-purple-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600">Total Recaudado</p>
                            <p class="text-xl font-bold text-purple-900">Bs. {{ number_format($totalAmount, 2) }}</p>
                        </div>
                        <div class="p-2 bg-purple-500 rounded-full">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido por Pestañas -->
            <x-content-card>
                <!-- Donaciones Pendientes -->
                <div x-show="activeTab === 'pending'" x-transition>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Donaciones Pendientes de Verificación</h3>
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">{{ $pendingCount }} pendientes</span>
                    </div>
                    @include('admin.donations.partials.donation-list', ['donationsList' => $donations->where('validation_status', 'pending'), 'showActions' => true])
                </div>

                <!-- Donaciones en Verificación -->
                <div x-show="activeTab === 'verifying'" x-transition>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Donaciones en Proceso de Verificación</h3>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">{{ $verifyingCount }} verificando</span>
                    </div>
                    @include('admin.donations.partials.donation-list', ['donationsList' => $donations->where('validation_status', 'verifying'), 'showActions' => true])
                </div>

                <!-- Donaciones Aprobadas -->
                <div x-show="activeTab === 'approved'" x-transition>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Donaciones Aprobadas</h3>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">{{ $approvedCount }} aprobadas</span>
                    </div>
                    @include('admin.donations.partials.donation-list', ['donationsList' => $donations->where('validation_status', 'approved'), 'showActions' => false])
                </div>

                <!-- Donaciones Rechazadas -->
                <div x-show="activeTab === 'rejected'" x-transition>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Donaciones Rechazadas</h3>
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">{{ $rejectedCount }} rechazadas</span>
                    </div>
                    @include('admin.donations.partials.donation-list', ['donationsList' => $donations->where('validation_status', 'rejected'), 'showActions' => false])
                </div>
            </x-content-card>
        </div>
    </div>

    <!-- Modal para Ver Detalles -->
    <div id="donationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Detalles de la Donación</h3>
                        <button onclick="closeDonationModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="donationDetails" class="space-y-4">
                        <!-- Contenido dinámico -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function donationManager() {
            return {
                activeTab: 'pending',
                donations: @json($donations->values()),
                
                showDonationDetails(donationId) {
                    const donation = this.donations.find(d => d.id === donationId);
                    if (!donation) return;
                    
                    const detailsHtml = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">Información Básica</h4>
                                    <div class="bg-gray-50 p-4 rounded-lg space-y-2 text-sm">
                                        <div><strong>Título:</strong> ${donation.title}</div>
                                        <div><strong>Descripción:</strong> ${donation.description || 'Sin descripción'}</div>
                                        <div><strong>Monto:</strong> <span class="text-green-600 font-semibold">Bs. ${parseFloat(donation.amount).toFixed(2)}</span></div>
                                        <div><strong>Categoría:</strong> <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">${this.getCategory(donation.amount)}</span></div>
                                        <div><strong>Puntos:</strong> <span class="text-blue-600 font-semibold">${this.calculatePoints(donation.amount)} puntos</span></div>
                                        <div><strong>Fecha:</strong> ${new Date(donation.donation_date).toLocaleDateString()}</div>
                                    </div>
                                </div>
                                
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">Estado de Validación</h4>
                                    <div class="bg-gray-50 p-4 rounded-lg space-y-2 text-sm">
                                        <div><strong>Estado:</strong> <span class="px-2 py-1 rounded text-xs font-medium ${this.getStatusBadgeClass(donation.validation_status)}">${this.getStatusText(donation.validation_status)}</span></div>
                                        ${donation.verification_code ? `<div><strong>Código:</strong> <span class="font-mono">${donation.verification_code}</span></div>` : ''}
                                        ${donation.verified_at ? `<div><strong>Verificado:</strong> ${new Date(donation.verified_at).toLocaleString()}</div>` : ''}
                                        ${donation.verified_by ? `<div><strong>Verificado por:</strong> ${donation.verified_by.name}</div>` : ''}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">Información de Pago</h4>
                                    <div class="bg-gray-50 p-4 rounded-lg space-y-2 text-sm">
                                        <div><strong>Método:</strong> ${this.getPaymentMethodText(donation.payment_method)}</div>
                                        ${donation.qr_code_data ? `
                                            <div class="mt-3">
                                                <strong>Datos del QR:</strong>
                                                <div class="bg-white p-3 rounded border text-xs mt-1">
                                                    <pre class="whitespace-pre-wrap">${JSON.stringify(JSON.parse(donation.qr_code_data), null, 2)}</pre>
                                                </div>
                                            </div>
                                        ` : ''}
                                        ${donation.bank_account_info ? `
                                            <div class="mt-3">
                                                <strong>Info Bancaria:</strong>
                                                <div class="bg-white p-3 rounded border text-xs mt-1">
                                                    ${donation.bank_account_info}
                                                </div>
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
                                
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-2">Usuario</h4>
                                    <div class="bg-gray-50 p-4 rounded-lg space-y-2 text-sm">
                                        <div><strong>Nombre:</strong> ${donation.user.name}</div>
                                        <div><strong>Email:</strong> ${donation.user.email}</div>
                                        <div><strong>Teléfono:</strong> ${donation.user.phone || 'No especificado'}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        ${donation.verification_notes ? `
                            <div class="mt-6">
                                <h4 class="font-semibold text-gray-900 mb-2">Notas de Verificación</h4>
                                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg text-sm">
                                    ${donation.verification_notes}
                                </div>
                            </div>
                        ` : ''}
                        
                        ${donation.notes ? `
                            <div class="mt-6">
                                <h4 class="font-semibold text-gray-900 mb-2">Notas Adicionales</h4>
                                <div class="bg-gray-50 p-4 rounded-lg text-sm">
                                    ${donation.notes}
                                </div>
                            </div>
                        ` : ''}
                    `;
                    
                    document.getElementById('donationDetails').innerHTML = detailsHtml;
                    document.getElementById('donationModal').classList.remove('hidden');
                },
                
                getCategory(amount) {
                    const amt = parseFloat(amount);
                    if (amt >= 1001) return 'Corporativa';
                    if (amt >= 501) return 'Grande';
                    if (amt >= 201) return 'Mediana';
                    if (amt >= 51) return 'Pequeña';
                    if (amt >= 1) return 'Micro';
                    return 'Sin categoría';
                },
                
                calculatePoints(amount) {
                    const amt = parseFloat(amount);
                    if (amt >= 1001) return 200;
                    if (amt >= 501) return 100;
                    if (amt >= 201) return 50;
                    if (amt >= 51) return 25;
                    if (amt >= 1) return 10;
                    return 0;
                },
                
                getStatusText(status) {
                    const statusMap = {
                        'pending': '⏳ Pendiente',
                        'verifying': '🔍 Verificando',
                        'approved': '✅ Aprobada',
                        'rejected': '❌ Rechazada'
                    };
                    return statusMap[status] || status;
                },
                
                getStatusBadgeClass(status) {
                    const classMap = {
                        'pending': 'bg-yellow-100 text-yellow-800',
                        'verifying': 'bg-blue-100 text-blue-800',
                        'approved': 'bg-green-100 text-green-800',
                        'rejected': 'bg-red-100 text-red-800'
                    };
                    return classMap[status] || 'bg-gray-100 text-gray-800';
                },
                
                getPaymentMethodText(method) {
                    const methodMap = {
                        'qr_code': '📱 Código QR',
                        'bank_transfer': '🏦 Transferencia Bancaria'
                    };
                    return methodMap[method] || 'Otro';
                }
            }
        }
        
        function closeDonationModal() {
            document.getElementById('donationModal').classList.add('hidden');
        }
        
        // Cerrar modal al hacer clic fuera
        document.getElementById('donationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDonationModal();
            }
        });
    </script>
</x-app-layout>
