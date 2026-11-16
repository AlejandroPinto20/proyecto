@props([
    'filters' => [],
    'searchUrl' => '',
    'placeholder' => 'Buscar...',
    'showFilters' => true
])

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6" x-data="advancedSearch()">
    <!-- Search Header -->
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Búsqueda Avanzada</h3>
        @if($showFilters)
            <button @click="showFilters = !showFilters" 
                    class="text-sm text-unifranz-orange hover:text-unifranz-orange-dark flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path>
                </svg>
                <span x-text="showFilters ? 'Ocultar Filtros' : 'Mostrar Filtros'"></span>
            </button>
        @endif
    </div>

    <!-- Main Search Bar -->
    <div class="relative mb-4">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <input type="text" 
               x-model="searchQuery"
               @input.debounce.300ms="performSearch()"
               class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-unifranz-orange focus:border-unifranz-orange"
               placeholder="{{ $placeholder }}">
        
        <!-- Clear Search Button -->
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
            <button @click="clearSearch()" 
                    x-show="searchQuery.length > 0"
                    class="text-gray-400 hover:text-gray-600">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Advanced Filters -->
    @if($showFilters)
        <div x-show="showFilters" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="border-t border-gray-200 pt-4">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                    <select x-model="filters.category" 
                            @change="performSearch()"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-unifranz-orange focus:border-unifranz-orange">
                        <option value="">Todas las categorías</option>
                        <option value="educacion">📚 Educación</option>
                        <option value="medio_ambiente">🌱 Medio Ambiente</option>
                        <option value="salud">🏥 Salud</option>
                        <option value="social">🤝 Social</option>
                        <option value="comunitario">🏘️ Comunitario</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select x-model="filters.status" 
                            @change="performSearch()"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-unifranz-orange focus:border-unifranz-orange">
                        <option value="">Todos los estados</option>
                        <option value="active">✅ Activa</option>
                        <option value="pending">⏳ Pendiente</option>
                        <option value="completed">🎉 Completada</option>
                        <option value="cancelled">❌ Cancelada</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                    <select x-model="filters.dateRange" 
                            @change="performSearch()"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-unifranz-orange focus:border-unifranz-orange">
                        <option value="">Cualquier fecha</option>
                        <option value="today">Hoy</option>
                        <option value="week">Esta semana</option>
                        <option value="month">Este mes</option>
                        <option value="custom">Rango personalizado</option>
                    </select>
                </div>

                <!-- Skills Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Habilidades</label>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-left focus:ring-2 focus:ring-unifranz-orange focus:border-unifranz-orange flex items-center justify-between">
                            <span x-text="filters.skills.length > 0 ? `${filters.skills.length} seleccionadas` : 'Seleccionar habilidades'"></span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                            <template x-for="skill in availableSkills" :key="skill.id">
                                <label class="flex items-center px-3 py-2 hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" 
                                           :value="skill.id"
                                           x-model="filters.skills"
                                           @change="performSearch()"
                                           class="text-unifranz-orange focus:ring-unifranz-orange">
                                    <span class="ml-2 text-sm" x-text="`${skill.emoji} ${skill.name}`"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Participants Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Participantes</label>
                    <select x-model="filters.participants" 
                            @change="performSearch()"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-unifranz-orange focus:border-unifranz-orange">
                        <option value="">Cualquier cantidad</option>
                        <option value="available">Con cupos disponibles</option>
                        <option value="small">Grupos pequeños (1-10)</option>
                        <option value="medium">Grupos medianos (11-25)</option>
                        <option value="large">Grupos grandes (25+)</option>
                    </select>
                </div>

                <!-- Sort By -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ordenar por</label>
                    <select x-model="filters.sortBy" 
                            @change="performSearch()"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-unifranz-orange focus:border-unifranz-orange">
                        <option value="created_at">Más recientes</option>
                        <option value="name">Nombre A-Z</option>
                        <option value="participants">Más populares</option>
                        <option value="start_date">Fecha de inicio</option>
                        <option value="relevance">Relevancia</option>
                    </select>
                </div>
            </div>

            <!-- Custom Date Range -->
            <div x-show="filters.dateRange === 'custom'" 
                 class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha desde</label>
                    <input type="date" 
                           x-model="filters.startDate"
                           @change="performSearch()"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-unifranz-orange focus:border-unifranz-orange">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha hasta</label>
                    <input type="date" 
                           x-model="filters.endDate"
                           @change="performSearch()"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-unifranz-orange focus:border-unifranz-orange">
                </div>
            </div>

            <!-- Active Filters Display -->
            <div x-show="hasActiveFilters()" class="mb-4">
                <div class="flex flex-wrap gap-2">
                    <span class="text-sm font-medium text-gray-700">Filtros activos:</span>
                    
                    <template x-for="filter in getActiveFilters()" :key="filter.key">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-unifranz-orange text-white">
                            <span x-text="filter.label"></span>
                            <button @click="removeFilter(filter.key)" class="ml-1 text-white hover:text-gray-200">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>
                    </template>
                    
                    <button @click="clearAllFilters()" 
                            class="text-xs text-gray-500 hover:text-gray-700 underline">
                        Limpiar todos
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Search Results Info -->
    <div x-show="searchPerformed" class="flex items-center justify-between text-sm text-gray-600">
        <span x-text="`${resultsCount} resultado${resultsCount !== 1 ? 's' : ''} encontrado${resultsCount !== 1 ? 's' : ''}`"></span>
        <span x-show="isLoading" class="flex items-center">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-unifranz-orange" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Buscando...
        </span>
    </div>
</div>

<script>
function advancedSearch() {
    return {
        searchQuery: '',
        showFilters: false,
        searchPerformed: false,
        isLoading: false,
        resultsCount: 0,
        filters: {
            category: '',
            status: '',
            dateRange: '',
            startDate: '',
            endDate: '',
            skills: [],
            participants: '',
            sortBy: 'created_at'
        },
        availableSkills: @json(\App\Models\Skill::select('id', 'name', 'emoji')->get()),

        async performSearch() {
            this.isLoading = true;
            this.searchPerformed = true;

            const params = new URLSearchParams();
            
            if (this.searchQuery) params.append('q', this.searchQuery);
            
            Object.entries(this.filters).forEach(([key, value]) => {
                if (value && value.length > 0) {
                    if (Array.isArray(value)) {
                        value.forEach(v => params.append(`${key}[]`, v));
                    } else {
                        params.append(key, value);
                    }
                }
            });

            try {
                const response = await fetch(`{{ $searchUrl }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                this.resultsCount = data.total || 0;
                
                // Dispatch custom event with results
                this.$dispatch('search-results', { 
                    results: data.data || data.results || [],
                    total: data.total || 0,
                    query: this.searchQuery,
                    filters: this.filters
                });

            } catch (error) {
                console.error('Search error:', error);
                this.resultsCount = 0;
            } finally {
                this.isLoading = false;
            }
        },

        clearSearch() {
            this.searchQuery = '';
            this.performSearch();
        },

        clearAllFilters() {
            this.filters = {
                category: '',
                status: '',
                dateRange: '',
                startDate: '',
                endDate: '',
                skills: [],
                participants: '',
                sortBy: 'created_at'
            };
            this.performSearch();
        },

        removeFilter(key) {
            if (Array.isArray(this.filters[key])) {
                this.filters[key] = [];
            } else {
                this.filters[key] = '';
            }
            this.performSearch();
        },

        hasActiveFilters() {
            return Object.values(this.filters).some(value => 
                Array.isArray(value) ? value.length > 0 : value !== ''
            );
        },

        getActiveFilters() {
            const active = [];
            const labels = {
                category: 'Categoría',
                status: 'Estado', 
                dateRange: 'Fecha',
                skills: 'Habilidades',
                participants: 'Participantes',
                sortBy: 'Orden'
            };

            Object.entries(this.filters).forEach(([key, value]) => {
                if (Array.isArray(value) && value.length > 0) {
                    active.push({
                        key,
                        label: `${labels[key]}: ${value.length} seleccionada${value.length > 1 ? 's' : ''}`
                    });
                } else if (value && value !== '' && key !== 'sortBy') {
                    active.push({
                        key,
                        label: `${labels[key]}: ${value}`
                    });
                }
            });

            return active;
        }
    }
}
</script>