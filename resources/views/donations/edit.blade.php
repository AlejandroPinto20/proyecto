<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Donación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('donations.update', $donation) }}" id="editDonationForm">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="mb-4">
                                <x-input-label for="title" :value="__('Título')" />
                                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $donation->title)" required autofocus autocomplete="title" />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="type" :value="__('Tipo de Donación')" />
                                <select id="type" name="type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="money" {{ old('type', $donation->type) === 'money' ? 'selected' : '' }}>💰 Dinero</option>
                                    <option value="material" {{ old('type', $donation->type) === 'material' ? 'selected' : '' }}>📦 Material</option>
                                    <option value="service" {{ old('type', $donation->type) === 'service' ? 'selected' : '' }}>🛠️ Servicio</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <!-- Service Details Section - Only for service donations -->
                            <div id="serviceSection" class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg" style="display: none;">
                                <h3 class="text-lg font-semibold mb-4 text-blue-800 dark:text-blue-200">Detalles del Servicio</h3>
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="service_type" :value="__('Tipo de Servicio')" />
                                        <select id="service_type" name="service_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="">Seleccionar tipo de servicio...</option>
                                            <option value="volunteer" {{ old('service_type', $donation->service_type) === 'volunteer' ? 'selected' : '' }}>Voluntariado</option>
                                            <option value="professional" {{ old('service_type', $donation->service_type) === 'professional' ? 'selected' : '' }}>Servicio Profesional</option>
                                            <option value="consulting" {{ old('service_type', $donation->service_type) === 'consulting' ? 'selected' : '' }}>Consultoría</option>
                                            <option value="other" {{ old('service_type', $donation->service_type) === 'other' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('service_type')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="service_hours" :value="__('Horas Estimadas')" />
                                        <x-text-input id="service_hours" class="block mt-1 w-full" type="number" step="0.5" name="service_hours" :value="old('service_hours', $donation->service_hours)" placeholder="Ej: 2.5" />
                                        <x-input-error :messages="$errors->get('service_hours')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- Material Details Section - Only for material donations -->
                            <div id="materialSection" class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg" style="display: none;">
                                <h3 class="text-lg font-semibold mb-4 text-green-800 dark:text-green-200">Detalles del Material</h3>
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="material_category" :value="__('Categoría del Material')" />
                                        <select id="material_category" name="material_category" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="">Seleccionar categoría...</option>
                                            <option value="food" {{ old('material_category', $donation->material_category) === 'food' ? 'selected' : '' }}>Alimentos</option>
                                            <option value="clothing" {{ old('material_category', $donation->material_category) === 'clothing' ? 'selected' : '' }}>Ropa</option>
                                            <option value="school_supplies" {{ old('material_category', $donation->school_supplies) === 'school_supplies' ? 'selected' : '' }}>Útiles Escolares</option>
                                            <option value="medical" {{ old('material_category', $donation->material_category) === 'medical' ? 'selected' : '' }}>Material Médico</option>
                                            <option value="construction" {{ old('material_category', $donation->material_category) === 'construction' ? 'selected' : '' }}>Material de Construcción</option>
                                            <option value="other" {{ old('material_category', $donation->material_category) === 'other' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('material_category')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="material_condition" :value="__('Condición del Material')" />
                                        <select id="material_condition" name="material_condition" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="new" {{ old('material_condition', $donation->material_condition) === 'new' ? 'selected' : '' }}>Nuevo</option>
                                            <option value="used_good" {{ old('material_condition', $donation->material_condition) === 'used_good' ? 'selected' : '' }}>Usado - Buen Estado</option>
                                            <option value="used_fair" {{ old('material_condition', $donation->material_condition) === 'used_fair' ? 'selected' : '' }}>Usado - Estado Regular</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('material_condition')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Descripción')" />
                            <textarea id="description" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="description" rows="3" placeholder="Describe tu donación...">{{ old('description', $donation->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="mb-4">
                                <x-input-label for="amount" :value="__('Monto/Cantidad')" />
                                <x-text-input id="amount" class="block mt-1 w-full" type="number" step="0.01" name="amount" :value="old('amount', $donation->amount)" required />
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="status" :value="__('Estado')" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="pending" {{ old('status', $donation->status) === 'pending' ? 'selected' : '' }}>⏳ Pendiente</option>
                                    <option value="approved" {{ old('status', $donation->status) === 'approved' ? 'selected' : '' }}>✅ Aprobado</option>
                                    <option value="completed" {{ old('status', $donation->status) === 'completed' ? 'selected' : '' }}>🎉 Completado</option>
                                    <option value="cancelled" {{ old('status', $donation->status) === 'cancelled' ? 'selected' : '' }}>❌ Cancelado</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="donation_date" :value="__('Fecha de Donación')" />
                                <x-text-input id="donation_date" class="block mt-1 w-full" type="date" name="donation_date" :value="old('donation_date', $donation->donation_date->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('donation_date')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Payment Method Section - Only for money donations -->
                        <div id="paymentMethodSection" class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg" style="display: none;">
                            <h3 class="text-lg font-semibold mb-4 text-yellow-800 dark:text-yellow-200">Método de Pago</h3>

                            <div class="mb-4">
                                <x-input-label for="payment_method" :value="__('Método de Pago')" />
                                <select id="payment_method" name="payment_method" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="cash" {{ old('payment_method', $donation->payment_method) === 'cash' ? 'selected' : '' }}>💵 Efectivo</option>
                                    <option value="qr_code" {{ old('payment_method', $donation->payment_method) === 'qr_code' ? 'selected' : '' }}>📱 Código QR</option>
                                </select>
                                <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                            </div>

                            <!-- Cash Payment Instructions -->
                            <div id="cashInstructions" class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border-l-4 border-blue-500" style="display: none;">
                                <h4 class="text-md font-semibold text-blue-800 dark:text-blue-200 mb-2">💰 Instrucciones para Pago en Efectivo</h4>
                                <div class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                    <p><strong>📍 Ubicación:</strong> Fundación UniFránz - Calle Los Cactus #123, La Paz, Bolivia</p>
                                    <p><strong>🕒 Horarios:</strong> Lunes a Viernes de 8:00 AM a 5:00 PM</p>
                                    <p><strong>📞 Contacto:</strong> (591) 2-1234567 | info@unifranz.edu.bo</p>
                                    <p><strong>📋 Procedimiento:</strong> Presentar este comprobante de donación al llegar a nuestras oficinas</p>
                                    <p class="text-orange-600 dark:text-orange-400 font-medium">⚠️ Importante: Traer el comprobante impreso o en tu dispositivo móvil</p>
                                </div>
                            </div>

                            <!-- QR Code Preview Section -->
                            <div id="qrCodeSection" class="text-center" style="display: none;">
                                <h4 class="text-md font-semibold mb-2">Código QR para Donación</h4>
                                <div id="qrCodeContainer" class="inline-block p-4 bg-white rounded-lg shadow-md">
                                    <div id="qrcode" class="mx-auto"></div>
                                    <p class="text-sm text-gray-600 mt-2">Escanea este código QR para realizar tu donación</p>
                                </div>
                                <div class="mt-4 text-sm text-gray-600">
                                    <p><strong>Monto:</strong> $<span id="qrAmount">0.00</span></p>
                                    <p><strong>Referencia:</strong> <span id="qrReference">DON-000000-0</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="notes" :value="__('Notas Adicionales')" />
                            <textarea id="notes" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="notes" rows="3" placeholder="Información adicional sobre tu donación...">{{ old('notes', $donation->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('donations.show', $donation) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">Cancelar</a>
                            <x-primary-button>
                                {{ __('Actualizar Donación') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const paymentMethodSection = document.getElementById('paymentMethodSection');
            const paymentMethodSelect = document.getElementById('payment_method');
            const bankInfoField = document.getElementById('bankInfoField');
            const qrCodeSection = document.getElementById('qrCodeSection');
            const amountInput = document.getElementById('amount');
            const titleInput = document.getElementById('title');

            let qrCode = null;

            // Show/hide sections based on donation type
            typeSelect.addEventListener('change', function() {
                const type = this.value;

                // Hide all sections first
                paymentMethodSection.style.display = 'none';
                serviceSection.style.display = 'none';
                materialSection.style.display = 'none';

                // Show relevant section
                if (type === 'money') {
                    paymentMethodSection.style.display = 'block';
                    paymentMethodSelect.required = true;
                } else if (type === 'service') {
                    serviceSection.style.display = 'block';
                } else if (type === 'material') {
                    materialSection.style.display = 'block';
                }

                // Reset payment method sections
                qrCodeSection.style.display = 'none';
                cashInstructions.style.display = 'none';
            });

            // Show/hide payment method sections based on payment method
            paymentMethodSelect.addEventListener('change', function() {
                const method = this.value;

                // Hide all payment sections first
                qrCodeSection.style.display = 'none';
                cashInstructions.style.display = 'none';

                // Show relevant section
                if (method === 'cash') {
                    cashInstructions.style.display = 'block';
                } else if (method === 'qr_code') {
                    qrCodeSection.style.display = 'block';
                    generateQRCode();
                }
            });

            // Generate QR code when amount or title changes
            function generateQRCode() {
                const amount = amountInput.value || '0.00';
                const title = titleInput.value || 'Donación';
                const timestamp = Date.now();
                const userId = {{ auth()->id() }};
                const reference = `DON-${timestamp}-${userId}`;

                // Update display values
                document.getElementById('qrAmount').textContent = parseFloat(amount).toFixed(2);
                document.getElementById('qrReference').textContent = reference;

                // Clear existing QR code
                const qrContainer = document.getElementById('qrcode');
                qrContainer.innerHTML = '';

                // Generate new QR code with donation data
                const qrData = JSON.stringify({
                    amount: parseFloat(amount),
                    description: title,
                    recipient: 'Fundación UniFránz',
                    reference: reference,
                    timestamp: timestamp
                });

                qrCode = new QRCode(qrContainer, {
                    text: qrData,
                    width: 200,
                    height: 200,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
            }

            // Regenerate QR code when amount or title changes
            amountInput.addEventListener('input', function() {
                if (paymentMethodSelect.value === 'qr_code') {
                    generateQRCode();
                }
            });

            titleInput.addEventListener('input', function() {
                if (paymentMethodSelect.value === 'qr_code') {
                    generateQRCode();
                }
            });

            // Initialize on page load
            if (typeSelect.value === 'money') {
                paymentMethodSection.style.display = 'block';
                paymentMethodSelect.required = true;

                // Show appropriate sections based on current payment method
                const currentMethod = paymentMethodSelect.value;
                if (currentMethod === 'cash') {
                    cashInstructions.style.display = 'block';
                } else if (currentMethod === 'qr_code') {
                    qrCodeSection.style.display = 'block';
                    generateQRCode();
                }
            } else if (typeSelect.value === 'service') {
                serviceSection.style.display = 'block';
            } else if (typeSelect.value === 'material') {
                materialSection.style.display = 'block';
            }
        });
    </script>
</x-app-layout>
