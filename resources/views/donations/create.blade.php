<x-app-layout>
    <x-page-header title="Nueva Donación" subtitle="Contribuye al cambio social y gana puntos por tu generosidad">
        <div class="flex items-center space-x-3">
            <x-action-button href="{{ route('donations.index') }}" variant="secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Donaciones
            </x-action-button>
        </div>
    </x-page-header>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('donations.store') }}" enctype="multipart/form-data" x-data="donationForm()">
                @csrf

                <!-- Información Básica -->
                <x-content-card header="Información de la Donación">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Título de la Donación *
                            </label>
                            <input type="text" id="title" name="title" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="Ej: Donación para útiles escolares"
                                   value="{{ old('title') }}" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción *
                            </label>
                            <textarea id="description" name="description" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                      placeholder="Describe el propósito y detalles de tu donación..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Monto (Bs.) *
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Bs.</span>
                                <input type="number" id="amount" name="amount" step="0.01" min="1"
                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                       placeholder="0.00" value="{{ old('amount') }}" x-model="amount" required>
                            </div>
                            
                            <!-- Calculadora de Puntos -->
                            <div class="mt-3 p-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-lg border border-green-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-gray-700">Puntos que ganarás:</div>
                                        <div class="text-lg font-bold text-green-600" x-text="calculatePoints(amount)"></div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-700">Categoría:</div>
                                        <div class="text-sm font-semibold text-blue-600" x-text="getCategory(amount)"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Montos Sugeridos -->
                            <div class="mt-3">
                                <div class="text-sm font-medium text-gray-700 mb-2">Montos sugeridos:</div>
                                <div class="grid grid-cols-3 gap-2">
                                    <button type="button" @click="amount = 50" class="px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                        Bs. 50 <span class="text-xs text-green-600">(10 pts)</span>
                                    </button>
                                    <button type="button" @click="amount = 100" class="px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                        Bs. 100 <span class="text-xs text-green-600">(25 pts)</span>
                                    </button>
                                    <button type="button" @click="amount = 250" class="px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                        Bs. 250 <span class="text-xs text-green-600">(50 pts)</span>
                                    </button>
                                </div>
                            </div>
                            
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="donation_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de Donación *
                            </label>
                            <input type="date" id="donation_date" name="donation_date"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                   value="{{ old('donation_date', date('Y-m-d')) }}" required>
                            @error('donation_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-content-card>

                <!-- Método de Pago -->
                <x-content-card header="Método de Pago" class="mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_method" value="qr_code" 
                                   class="sr-only peer" x-model="paymentMethod" required>
                            <div class="p-6 border-2 border-gray-200 rounded-lg peer-checked:border-orange-500 peer-checked:bg-orange-50 hover:border-orange-300 transition-all">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <span class="text-white text-xl">📱</span>
                                    </div>
                                    <h3 class="font-semibold text-gray-900">Código QR</h3>
                                    <p class="text-sm text-gray-600">Pago digital instantáneo</p>
                                </div>
                            </div>
                        </label>

                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_method" value="bank_transfer" 
                                   class="sr-only peer" x-model="paymentMethod">
                            <div class="p-6 border-2 border-gray-200 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-blue-300 transition-all">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <span class="text-white text-xl">🏦</span>
                                    </div>
                                    <h3 class="font-semibold text-gray-900">Transferencia Bancaria</h3>
                                    <p class="text-sm text-gray-600">Depósito o transferencia</p>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- QR Code Section -->
                    <div x-show="paymentMethod === 'qr_code'" x-transition class="bg-gray-50 p-6 rounded-lg">
                        <div class="text-center">
                            <h4 class="font-semibold text-gray-900 mb-4">Código QR para Donación</h4>
                            <div class="inline-block p-4 bg-white rounded-lg shadow-sm border">
                                <div id="qrcode" class="mx-auto"></div>
                            </div>
                            <p class="text-sm text-gray-600 mt-4">
                                Escanea este código QR con tu aplicación bancaria para realizar el pago
                            </p>
                            <div class="mt-4 p-3 bg-orange-100 rounded-lg">
                                <p class="text-sm text-orange-800">
                                    <strong>Monto:</strong> Bs. <span x-text="amount || '0.00'"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Transfer Section -->
                    <div x-show="paymentMethod === 'bank_transfer'" x-transition class="bg-blue-50 p-6 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-4">Información Bancaria</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div class="space-y-2">
                                <p><strong>Banco:</strong> Banco Unión</p>
                                <p><strong>Tipo de Cuenta:</strong> Cuenta Corriente</p>
                                <p><strong>Número:</strong> 1234567890</p>
                            </div>
                            <div class="space-y-2">
                                <p><strong>Beneficiario:</strong> Fundación UNIFRANZ</p>
                                <p><strong>NIT:</strong> 123456789</p>
                                <p><strong>Concepto:</strong> Donación</p>
                            </div>
                        </div>
                        <div class="mt-4 p-3 bg-yellow-100 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                <strong>Importante:</strong> Después de realizar la transferencia, guarda el comprobante para presentarlo cuando sea requerido.
                            </p>
                        </div>
                    </div>

                    @error('payment_method')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </x-content-card>



                <!-- Notas Adicionales -->
                <x-content-card header="Información Adicional (Opcional)" class="mt-6">
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notas o Comentarios
                        </label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                  placeholder="Información adicional, restricciones o comentarios especiales...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </x-content-card>

                <!-- Campos ocultos -->
                <input type="hidden" name="type" value="money">
                <input type="hidden" name="status" value="pending">

                <!-- Información Importante -->
                <div class="mt-6 space-y-4">
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <h4 class="font-medium text-yellow-800 mb-1">Restricción Importante</h4>
                                <p class="text-sm text-yellow-700">
                                    Solo puedes tener <strong>una donación en proceso</strong> a la vez. Debes esperar a que tu donación actual sea aprobada o rechazada antes de crear una nueva.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="font-medium text-blue-800 mb-1">Proceso de Verificación</h4>
                                <p class="text-sm text-blue-700">
                                    Las donaciones son verificadas manualmente por administradores. Recibirás un código de verificación y notificaciones sobre el estado de tu donación.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumen de la Donación -->
                <div class="mt-6 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen de tu Donación</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-white rounded-lg">
                            <div class="text-2xl font-bold text-green-600" x-text="'Bs. ' + (parseFloat(amount) || 0).toFixed(2)"></div>
                            <div class="text-sm text-gray-600">Monto a donar</div>
                        </div>
                        <div class="text-center p-4 bg-white rounded-lg">
                            <div class="text-2xl font-bold text-blue-600" x-text="calculatePoints(amount)"></div>
                            <div class="text-sm text-gray-600">Puntos a ganar</div>
                        </div>
                        <div class="text-center p-4 bg-white rounded-lg">
                            <div class="text-2xl font-bold text-purple-600" x-text="getCategory(amount)"></div>
                            <div class="text-sm text-gray-600">Categoría</div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="mt-8 flex justify-end space-x-4">
                    <x-action-button href="{{ route('donations.index') }}" variant="secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancelar
                    </x-action-button>
                    <x-action-button type="submit" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Crear Donación
                    </x-action-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        function donationForm() {
            return {
                paymentMethod: '{{ old('payment_method', 'qr_code') }}',
                amount: '{{ old('amount') }}',
                qrCode: null,

                init() {
                    this.$watch('paymentMethod', () => {
                        if (this.paymentMethod === 'qr_code') {
                            this.$nextTick(() => this.generateQR());
                        }
                    });
                    
                    this.$watch('amount', () => {
                        if (this.paymentMethod === 'qr_code') {
                            this.generateQR();
                        }
                    });

                    // Generate initial QR if QR method is selected
                    if (this.paymentMethod === 'qr_code') {
                        this.$nextTick(() => this.generateQR());
                    }
                },

                generateQR() {
                    const container = document.getElementById('qrcode');
                    if (!container) return;
                    
                    container.innerHTML = '';
                    
                    const qrData = {
                        amount: parseFloat(this.amount) || 0,
                        recipient: 'Fundación UNIFRANZ',
                        account: '1234567890',
                        bank: 'Banco Unión',
                        reference: `DON-${Date.now()}-{{ auth()->id() }}`
                    };

                    new QRCode(container, {
                        text: JSON.stringify(qrData),
                        width: 200,
                        height: 200,
                        colorDark: '#000000',
                        colorLight: '#ffffff',
                        correctLevel: QRCode.CorrectLevel.H
                    });
                },

                calculatePoints(amount) {
                    const amt = parseFloat(amount) || 0;
                    if (amt >= 1001) return '200 puntos';
                    if (amt >= 501) return '100 puntos';
                    if (amt >= 201) return '50 puntos';
                    if (amt >= 51) return '25 puntos';
                    if (amt >= 1) return '10 puntos';
                    return '0 puntos';
                },

                getCategory(amount) {
                    const amt = parseFloat(amount) || 0;
                    if (amt >= 1001) return 'Corporativa';
                    if (amt >= 501) return 'Grande';
                    if (amt >= 201) return 'Mediana';
                    if (amt >= 51) return 'Pequeña';
                    if (amt >= 1) return 'Micro';
                    return '-';
                }
            }
        }
    </script>
</x-app-layout>