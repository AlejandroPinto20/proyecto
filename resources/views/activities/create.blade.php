<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-400 via-purple-400 to-orange-400 py-8 relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full mix-blend-overlay filter blur-xl opacity-10 animate-blob"></div>
            <div class="absolute top-40 right-10 w-72 h-72 bg-white rounded-full mix-blend-overlay filter blur-xl opacity-10 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-white rounded-full mix-blend-overlay filter blur-xl opacity-10 animate-blob animation-delay-4000"></div>
        </div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Header -->
            <div class="mb-8 text-center">
                <div class="inline-block p-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl shadow-lg mb-4 animate-bounce-slow">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 mb-3">Crear Nueva Actividad</h1>
                <p class="text-gray-600 text-lg">✨ Define una nueva actividad de voluntariado con skills asociados</p>
            </div>

            <form action="{{ route('activities.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Información Básica -->
                <div class="bg-white rounded-2xl shadow-xl border-2 border-blue-100 p-6 hover:shadow-2xl transition-all duration-300 hover:scale-[1.01]">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Información Básica
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Título de la Actividad</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('title') border-red-500 @enderror" placeholder="Ej: Limpieza de Parque Central" required>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                            <select name="category" id="category" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror" required>
                                <option value="">Selecciona una categoría</option>
                                <option value="voluntariado" {{ old('category') == 'voluntariado' ? 'selected' : '' }}>🤝 Voluntariado</option>
                                <option value="capacitacion" {{ old('category') == 'capacitacion' ? 'selected' : '' }}>📚 Capacitación</option>
                                <option value="evento" {{ old('category') == 'evento' ? 'selected' : '' }}>🎉 Evento</option>
                                <option value="taller" {{ old('category') == 'taller' ? 'selected' : '' }}>🔧 Taller</option>
                                <option value="proyecto" {{ old('category') == 'proyecto' ? 'selected' : '' }}>🚀 Proyecto</option>
                            </select>
                            @error('category')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                        <textarea name="description" id="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror" placeholder="Describe detalladamente la actividad, objetivos y qué se espera de los participantes...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Fechas y Ubicación -->
                <div class="bg-white rounded-2xl shadow-xl border-2 border-green-100 p-6 hover:shadow-2xl transition-all duration-300 hover:scale-[1.01]">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6"></path>
                        </svg>
                        Programación y Ubicación
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha y Hora de Inicio</label>
                            <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_date') border-red-500 @enderror" onchange="validateDates()">
                            <p class="text-sm text-gray-500 mt-1" id="start-date-info">Debe ser una fecha futura</p>
                            @error('start_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha y Hora de Fin</label>
                            <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('end_date') border-red-500 @enderror" onchange="validateDates()">
                            <p class="text-sm text-gray-500 mt-1" id="end-date-info">Debe ser posterior al inicio</p>
                            @error('end_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-blue-50 rounded-xl" id="duration-info" style="display: none;">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-blue-800 font-medium" id="duration-text"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Ubicación</label>
                            <input type="text" name="location" id="location" value="{{ old('location') }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location') border-red-500 @enderror" placeholder="Buscar ubicación..." onchange="searchLocation()">
                            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                            @error('location')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-2">Máximo de Participantes</label>
                            <div class="relative">
                                <input type="number" name="max_participants" id="max_participants" value="{{ old('max_participants') }}" min="1" max="1000" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('max_participants') border-red-500 @enderror" placeholder="Ej: 50" onchange="updateParticipantInfo()">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mt-1" id="participant-info">Deja vacío para sin límite</p>
                            @error('max_participants')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Mapa Interactivo -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ubicación en el Mapa</label>
                        <div id="map" class="w-full h-64 rounded-xl border border-gray-300"></div>
                        <p class="text-sm text-gray-500 mt-2">Haz clic en el mapa para seleccionar la ubicación exacta</p>
                    </div>
                </div>

                <!-- Skills Desarrollados -->
                <div class="bg-white rounded-2xl shadow-xl border-2 border-purple-100 p-6 hover:shadow-2xl transition-all duration-300 hover:scale-[1.01]">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Skills que se Desarrollan
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($skills as $skill)
                            <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 hover:border-purple-300 cursor-pointer transition-all duration-300 hover:scale-105 hover:shadow-md">
                                <input type="checkbox" name="skills[]" value="{{ $skill->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-3" {{ in_array($skill->id, old('skills', [])) ? 'checked' : '' }}>
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <span class="text-lg mr-2">{{ $skill->getEmoji() }}</span>
                                        <span class="font-medium text-gray-900">{{ $skill->name }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $skill->category }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('skills')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Configuración Avanzada -->
                <div class="bg-white rounded-2xl shadow-xl border-2 border-orange-100 p-6 hover:shadow-2xl transition-all duration-300 hover:scale-[1.01]">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        </svg>
                        Configuración
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="registration_type" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Registro</label>
                            <select name="registration_type" id="registration_type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="open" {{ old('registration_type', 'open') == 'open' ? 'selected' : '' }}>Registro Abierto</option>
                                <option value="approval_required" {{ old('registration_type') == 'approval_required' ? 'selected' : '' }}>Requiere Aprobación</option>
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Estado Inicial</label>
                            <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="proxima" {{ old('status', 'proxima') == 'proxima' ? 'selected' : '' }}>🟡 Próxima</option>
                                <option value="abierta" {{ old('status') == 'abierta' ? 'selected' : '' }}>🟢 Abierta</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex items-center justify-between pt-6">
                    <a href="{{ route('activities.index') }}" class="px-6 py-3 text-gray-600 hover:text-gray-800 font-medium transition-colors duration-200">
                        Cancelar
                    </a>
                    <button type="submit" class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-white px-8 py-3 rounded-2xl font-bold hover:from-blue-700 hover:via-purple-700 hover:to-pink-700 transition-all duration-300 transform hover:scale-110 shadow-xl hover:shadow-2xl overflow-hidden group">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Crear Actividad
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        @keyframes blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(20px, -50px) scale(1.1); }
            50% { transform: translate(-20px, 20px) scale(0.9); }
            75% { transform: translate(50px, 50px) scale(1.05); }
        }
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        .animate-bounce-slow {
            animation: bounce-slow 3s ease-in-out infinite;
        }
    </style>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        let map;
        let marker;
        
        // Inicializar mapa
        document.addEventListener('DOMContentLoaded', function() {
            // Coordenadas de La Paz, Bolivia
            const defaultLat = -16.5000;
            const defaultLng = -68.1500;
            
            map = L.map('map').setView([defaultLat, defaultLng], 13);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            // Agregar marcador al hacer clic
            map.on('click', function(e) {
                if (marker) {
                    map.removeLayer(marker);
                }
                
                marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
                document.getElementById('latitude').value = e.latlng.lat;
                document.getElementById('longitude').value = e.latlng.lng;
                
                // Geocodificación inversa
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.display_name) {
                            document.getElementById('location').value = data.display_name;
                        }
                    });
            });
            
            // Configurar fechas mínimas
            const now = new Date();
            const tomorrow = new Date(now.getTime() + 24 * 60 * 60 * 1000);
            const minDateTime = tomorrow.toISOString().slice(0, 16);
            
            document.getElementById('start_date').min = minDateTime;
            document.getElementById('end_date').min = minDateTime;
        });
        
        // Buscar ubicación
        function searchLocation() {
            const location = document.getElementById('location').value;
            if (!location) return;
            
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(location)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lng = parseFloat(data[0].lon);
                        
                        map.setView([lat, lng], 15);
                        
                        if (marker) {
                            map.removeLayer(marker);
                        }
                        
                        marker = L.marker([lat, lng]).addTo(map);
                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lng;
                    }
                });
        }
        
        // Validar fechas
        function validateDates() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const startInfo = document.getElementById('start-date-info');
            const endInfo = document.getElementById('end-date-info');
            const durationInfo = document.getElementById('duration-info');
            const durationText = document.getElementById('duration-text');
            
            if (startDate) {
                const start = new Date(startDate);
                const now = new Date();
                
                if (start > now) {
                    startInfo.textContent = 'Fecha válida';
                    startInfo.className = 'text-sm text-green-500 mt-1';
                } else {
                    startInfo.textContent = 'La fecha debe ser futura';
                    startInfo.className = 'text-sm text-red-500 mt-1';
                }
                
                // Actualizar fecha mínima de fin
                document.getElementById('end_date').min = startDate;
            }
            
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                
                if (end > start) {
                    endInfo.textContent = 'Fecha válida';
                    endInfo.className = 'text-sm text-green-500 mt-1';
                    
                    // Calcular duración
                    const diffMs = end - start;
                    const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
                    const diffDays = Math.floor(diffHours / 24);
                    const remainingHours = diffHours % 24;
                    
                    let durationStr = '';
                    if (diffDays > 0) {
                        durationStr += `${diffDays} día${diffDays > 1 ? 's' : ''}`;
                        if (remainingHours > 0) {
                            durationStr += ` y ${remainingHours} hora${remainingHours > 1 ? 's' : ''}`;
                        }
                    } else {
                        durationStr = `${diffHours} hora${diffHours > 1 ? 's' : ''}`;
                    }
                    
                    durationText.textContent = `Duración: ${durationStr}`;
                    durationInfo.style.display = 'block';
                } else {
                    endInfo.textContent = 'Debe ser posterior al inicio';
                    endInfo.className = 'text-sm text-red-500 mt-1';
                    durationInfo.style.display = 'none';
                }
            }
        }
        
        // Actualizar información de participantes
        function updateParticipantInfo() {
            const maxParticipants = document.getElementById('max_participants').value;
            const info = document.getElementById('participant-info');
            
            if (maxParticipants) {
                const num = parseInt(maxParticipants);
                if (num > 0) {
                    info.textContent = `Máximo ${num} participante${num > 1 ? 's' : ''}`;
                    info.className = 'text-sm text-blue-600 mt-1';
                } else {
                    info.textContent = 'Número inválido';
                    info.className = 'text-sm text-red-500 mt-1';
                }
            } else {
                info.textContent = 'Sin límite de participantes';
                info.className = 'text-sm text-gray-500 mt-1';
            }
        }
    </script>
</x-app-layout>