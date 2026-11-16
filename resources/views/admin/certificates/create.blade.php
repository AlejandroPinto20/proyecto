<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-gradient-to-r from-green-500 to-blue-600 rounded-full">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Crear Nuevo Certificado') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Emitir certificado de voluntariado para reconocer el compromiso comunitario
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-white via-orange-50 to-red-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Certificate Type Selection -->
            <div class="mb-6 grid grid-cols-2 gap-4">
                <button onclick="showIndividualForm()" id="btn-individual" class="p-6 bg-white dark:bg-gray-800 rounded-xl border-2 border-blue-500 shadow-lg hover:shadow-xl transition-all">
                    <div class="flex flex-col items-center space-y-3">
                        <div class="p-3 bg-blue-500 rounded-full">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Certificado Individual</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">Crear certificado para un voluntario específico</p>
                    </div>
                </button>
                <button onclick="showBulkForm()" id="btn-bulk" class="p-6 bg-white dark:bg-gray-800 rounded-xl border-2 border-gray-300 shadow-lg hover:shadow-xl transition-all">
                    <div class="flex flex-col items-center space-y-3">
                        <div class="p-3 bg-green-500 rounded-full">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Certificados Masivos</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">Crear certificados para todos los participantes de una actividad</p>
                    </div>
                </button>
            </div>
            <!-- Individual Certificate Form -->
            <div id="individual-form" class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="px-8 py-6 bg-gradient-to-r from-green-500 to-blue-600">
                    <div class="flex items-center">
                        <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-white">Nuevo Certificado de Voluntariado</h3>
                            <p class="text-green-100 text-sm">Complete los detalles para generar el certificado</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.certificates.store') }}" class="p-8 space-y-6">
                    @csrf

                    <!-- User Selection -->
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <div class="p-2 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <label for="user_id" class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ __('Seleccionar Voluntario') }}
                            </label>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 ml-10">Elija el voluntario que recibirá el certificado</p>
                        <select id="user_id" name="user_id"
                                class="block w-full mt-2 px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-gray-900 dark:text-white"
                                required>
                            <option value="">Seleccionar voluntario...</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                    </div>

                    <!-- Activity Selection -->
                    <div id="activity-section" class="space-y-2" style="display: none;">
                        <div class="flex items-center space-x-2">
                            <div class="p-2 bg-gradient-to-r from-green-500 to-teal-500 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <label for="activity_select" class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ __('Seleccionar Actividad Completada') }}
                            </label>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 ml-10">Actividades que el voluntario ha completado</p>
                        <select id="activity_select"
                                class="block w-full mt-2 px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 dark:text-white">
                            <option value="">Cargando actividades...</option>
                        </select>
                        <input type="hidden" id="activity_title" name="activity_title" value="{{ old('activity_title') }}" />
                        <input type="hidden" id="hours_volunteered" name="hours_volunteered" value="{{ old('hours_volunteered') }}" />
                    </div>

                    <!-- Activity Info Display -->
                    <div id="activity-info" class="space-y-3" style="display: none;">
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 rounded-xl p-4 border-l-4 border-blue-500">
                            <div class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Información de la Actividad</h4>
                                    <div class="space-y-1 text-sm text-gray-700 dark:text-gray-300">
                                        <p><span class="font-medium">Actividad:</span> <span id="display-activity"></span></p>
                                        <p><span class="font-medium">Horas:</span> <span id="display-hours"></span></p>
                                        <p><span class="font-medium">Fecha:</span> <span id="display-dates"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Issued Date -->
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <div class="p-2 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <label for="issued_date" class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ __('Fecha de Emisión') }}
                            </label>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 ml-10">Fecha en que se emite el certificado</p>
                        <input id="issue_date" type="date" name="issue_date"
                               value="{{ old('issue_date', date('Y-m-d')) }}"
                               class="block w-full mt-2 px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200 text-gray-900 dark:text-white"
                               required />
                        <x-input-error :messages="$errors->get('issue_date')" class="mt-2" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.certificates') }}"
                           class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200 font-medium">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-green-500 to-blue-600 text-white rounded-xl hover:from-green-600 hover:to-blue-700 transition duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <span class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ __('Crear Certificado') }}</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Bulk Certificate Form -->
            <div id="bulk-form" class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700" style="display: none;">
                <div class="px-8 py-6 bg-gradient-to-r from-green-500 to-teal-600">
                    <div class="flex items-center">
                        <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-white">Certificados Masivos por Actividad</h3>
                            <p class="text-green-100 text-sm">Generar certificados para todos los participantes</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.certificates.store-bulk') }}" class="p-8 space-y-6">
                    @csrf

                    <!-- Activity Selection -->
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <div class="p-2 bg-gradient-to-r from-green-500 to-teal-500 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <label for="bulk_activity_id" class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ __('Seleccionar Actividad Completada') }}
                            </label>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 ml-10">Actividades completadas con participantes</p>
                        <select id="bulk_activity_id" name="activity_id"
                                class="block w-full mt-2 px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 text-gray-900 dark:text-white"
                                required>
                            <option value="">Seleccionar actividad...</option>
                            @php
                                $completedActivities = \App\Models\Activity::with(['users' => function($q) { 
                                        $q->whereIn('activity_user.status', ['joined', 'completed']); 
                                    }])
                                    ->get()
                                    ->filter(function($activity) {
                                        return $activity->users->count() > 0;
                                    });
                            @endphp
                            @foreach($completedActivities as $activity)
                            <option value="{{ $activity->id }}" data-hours="{{ $activity->start_date && $activity->end_date ? $activity->start_date->diffInHours($activity->end_date) : 0 }}" data-participants="{{ $activity->users->count() }}">
                                {{ $activity->name }} ({{ $activity->users->count() }} participantes)
                            </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('activity_id')" class="mt-2" />
                    </div>

                    <!-- Bulk Activity Info -->
                    <div id="bulk-activity-info" class="space-y-3" style="display: none;">
                        <div class="bg-gradient-to-r from-green-50 to-teal-50 dark:from-gray-700 dark:to-gray-600 rounded-xl p-4 border-l-4 border-green-500">
                            <div class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Información de Certificados</h4>
                                    <div class="space-y-1 text-sm text-gray-700 dark:text-gray-300">
                                        <p><span class="font-medium">Participantes:</span> <span id="bulk-participants"></span></p>
                                        <p><span class="font-medium">Horas por certificado:</span> <span id="bulk-hours"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Issued Date -->
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <div class="p-2 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <label for="bulk_issue_date" class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ __('Fecha de Emisión') }}
                            </label>
                        </div>
                        <input id="bulk_issue_date" type="date" name="issue_date"
                               value="{{ old('issue_date', date('Y-m-d')) }}"
                               class="block w-full mt-2 px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200 text-gray-900 dark:text-white"
                               required />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.certificates') }}"
                           class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200 font-medium">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-green-500 to-teal-600 text-white rounded-xl hover:from-green-600 hover:to-teal-700 transition duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <span class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ __('Crear Certificados') }}</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function showIndividualForm() {
        document.getElementById('individual-form').style.display = 'block';
        document.getElementById('bulk-form').style.display = 'none';
        document.getElementById('btn-individual').classList.add('border-blue-500');
        document.getElementById('btn-individual').classList.remove('border-gray-300');
        document.getElementById('btn-bulk').classList.remove('border-green-500');
        document.getElementById('btn-bulk').classList.add('border-gray-300');
    }
    
    function showBulkForm() {
        document.getElementById('individual-form').style.display = 'none';
        document.getElementById('bulk-form').style.display = 'block';
        document.getElementById('btn-bulk').classList.add('border-green-500');
        document.getElementById('btn-bulk').classList.remove('border-gray-300');
        document.getElementById('btn-individual').classList.remove('border-blue-500');
        document.getElementById('btn-individual').classList.add('border-gray-300');
    }
    
    document.getElementById('bulk_activity_id').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const bulkInfo = document.getElementById('bulk-activity-info');
        
        if (!this.value) {
            bulkInfo.style.display = 'none';
            return;
        }
        
        const participants = option.dataset.participants;
        const hours = option.dataset.hours;
        
        document.getElementById('bulk-participants').textContent = participants + ' voluntarios';
        document.getElementById('bulk-hours').textContent = hours + ' horas';
        bulkInfo.style.display = 'block';
    });
    
    document.getElementById('user_id').addEventListener('change', function() {
        const userId = this.value;
        const activitySection = document.getElementById('activity-section');
        const activitySelect = document.getElementById('activity_select');
        const activityInfo = document.getElementById('activity-info');
        
        if (!userId) {
            activitySection.style.display = 'none';
            activityInfo.style.display = 'none';
            return;
        }
        
        activitySection.style.display = 'block';
        activitySelect.innerHTML = '<option value="">Cargando actividades...</option>';
        
        fetch(`/admin/users/${userId}/completed-activities`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    activitySelect.innerHTML = '<option value="">No hay actividades completadas</option>';
                    return;
                }
                
                activitySelect.innerHTML = '<option value="">Seleccionar actividad...</option>';
                data.forEach(activity => {
                    const option = document.createElement('option');
                    option.value = JSON.stringify(activity);
                    option.textContent = `${activity.name} (${activity.hours}h)`;
                    activitySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                activitySelect.innerHTML = '<option value="">Error al cargar actividades</option>';
            });
    });
    
    document.getElementById('activity_select').addEventListener('change', function() {
        const activityInfo = document.getElementById('activity-info');
        
        if (!this.value) {
            activityInfo.style.display = 'none';
            return;
        }
        
        const activity = JSON.parse(this.value);
        document.getElementById('activity_title').value = activity.name;
        document.getElementById('hours_volunteered').value = activity.hours;
        document.getElementById('display-activity').textContent = activity.name;
        document.getElementById('display-hours').textContent = activity.hours + ' horas';
        document.getElementById('display-dates').textContent = activity.dates;
        activityInfo.style.display = 'block';
    });
    </script>
</x-app-layout>
