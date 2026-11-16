<x-app-layout>
    <x-page-header title="Calendario de Actividades" subtitle="Gestiona y participa en actividades de voluntariado" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tabs Navigation -->
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button id="calendar-tab" class="tab-button active border-b-2 border-orange-500 text-orange-600 py-2 px-1 text-sm font-medium">
                            📅 Vista Calendario
                        </button>
                        <button id="list-tab" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 py-2 px-1 text-sm font-medium">
                            📋 Vista Lista
                        </button>
                        <button id="stats-tab" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 py-2 px-1 text-sm font-medium">
                            📊 Estadísticas
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Calendar Tab Content -->
            <div id="calendar-content" class="tab-content">
                <x-content-card>
                    <div class="p-6">
                        <div class="bg-white rounded-xl shadow-lg">
                            <div class="p-6 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-t-xl">
                                <h3 class="text-2xl font-bold mb-2">Calendario de Actividades</h3>
                                <p>Visualiza y participa en actividades de voluntariado</p>
                            </div>
                            <div class="p-6">
                                <div id="calendar" style="min-height: 500px;"></div>
                            </div>
                        </div>
                    </div>
                </x-content-card>
            </div>

            <!-- List Tab Content -->
            <div id="list-content" class="tab-content hidden">
                <x-content-card>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-4">Lista de Actividades</h3>
                        
                        <!-- Search -->
                        <div class="mb-4">
                            <input type="text" id="searchInput" placeholder="Buscar actividades..." 
                                   class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <!-- Activities List -->
                        <div id="activitiesList" class="space-y-4">
                            @foreach($activities as $activity)
                            <div class="bg-white p-4 rounded-lg shadow border">
                                <h4 class="font-semibold">{{ $activity->name }}</h4>
                                <p class="text-gray-600">{{ $activity->description }}</p>
                                <div class="mt-2 text-sm text-gray-500">
                                    <span class="inline-block px-2 py-1 bg-{{ $activity->status === 'active' ? 'green' : 'gray' }}-100 rounded">
                                        {{ ucfirst($activity->status) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </x-content-card>
            </div>

            <!-- Stats Tab Content -->
            <div id="stats-content" class="tab-content hidden">
                <x-content-card>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-4">Estadísticas</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-blue-500 text-white p-4 rounded-lg">
                                <h4 class="text-lg font-semibold">Total</h4>
                                <p class="text-2xl font-bold">{{ $activities->count() }}</p>
                            </div>
                            <div class="bg-green-500 text-white p-4 rounded-lg">
                                <h4 class="text-lg font-semibold">Activas</h4>
                                <p class="text-2xl font-bold">{{ $activities->where('status', 'active')->count() }}</p>
                            </div>
                            <div class="bg-purple-500 text-white p-4 rounded-lg">
                                <h4 class="text-lg font-semibold">Mis Participaciones</h4>
                                <p class="text-2xl font-bold">{{ auth()->user()->activities()->count() }}</p>
                            </div>
                            <div class="bg-orange-500 text-white p-4 rounded-lg">
                                <h4 class="text-lg font-semibold">Completadas</h4>
                                <p class="text-2xl font-bold">{{ $activities->where('status', 'completed')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </x-content-card>
            </div>
        </div>
    </div>

    <!-- FullCalendar Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active from all
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'border-orange-500', 'text-orange-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });
                    
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Add active to clicked
                    this.classList.add('active', 'border-orange-500', 'text-orange-600');
                    this.classList.remove('border-transparent', 'text-gray-500');

                    // Show content
                    const tabId = this.id.replace('-tab', '');
                    const content = document.getElementById(tabId + '-content');
                    if (content) {
                        content.classList.remove('hidden');
                    }
                });
            });

            // Initialize calendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: {
                    url: '{{ route("calendar.activities") }}',
                    method: 'GET'
                },
                eventClick: function(info) {
                    alert('Actividad: ' + info.event.title);
                }
            });

            calendar.render();

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const activities = document.querySelectorAll('#activitiesList > div');
                    
                    activities.forEach(activity => {
                        const text = activity.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            activity.style.display = 'block';
                        } else {
                            activity.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
</x-app-layout>