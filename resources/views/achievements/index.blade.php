<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header title="🏆 Centro de Logros" subtitle="Descubre todos los logros disponibles y tu progreso">
                <x-slot name="actions">
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                        <span class="text-sm text-gray-600">Desbloqueados:</span>
                        <span class="font-bold text-blue-600">{{ count($unlockedAchievements) }}/{{ count($allAchievements) }}</span>
                    </div>
                </x-slot>
            </x-page-header>

            <!-- Progress Overview -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-purple-600 via-pink-600 to-indigo-600 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-white">Tu Progreso General</h3>
                            <p class="text-purple-100">{{ round((count($unlockedAchievements) / count($allAchievements)) * 100, 1) }}% completado</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-white">{{ count($unlockedAchievements) }}</div>
                            <div class="text-purple-100">de {{ count($allAchievements) }} logros</div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="w-full bg-white bg-opacity-20 rounded-full h-3">
                            <div class="bg-white h-3 rounded-full transition-all duration-500" 
                                 style="width: {{ (count($unlockedAchievements) / count($allAchievements)) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievement Categories -->
            @php
                $categories = collect($allAchievements)->groupBy('category');
            @endphp

            @foreach($categories as $categoryName => $achievements)
            <div class="mb-8">
                <div class="flex items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        @switch($categoryName)
                            @case('Actividades') 🎯 @break
                            @case('Puntos') 💎 @break
                            @case('Tiempo') ⏰ @break
                            @case('Habilidades') 🧠 @break
                            @case('Donaciones') ❤️ @break
                            @case('Especial') ⭐ @break
                        @endswitch
                        {{ $categoryName }}
                    </h2>
                    <div class="ml-4 bg-gray-100 rounded-full px-3 py-1">
                        <span class="text-sm font-medium text-gray-600">
                            {{ $achievements->where('unlocked', true)->count() }}/{{ $achievements->count() }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($achievements as $achievement)
                        <div class="relative bg-white rounded-xl shadow-lg border-2 overflow-hidden transition-all duration-300 hover:shadow-xl
                            @if($achievement['unlocked']) 
                                border-green-300 bg-gradient-to-br from-green-50 to-emerald-50
                            @else 
                                border-gray-200 hover:border-gray-300
                            @endif">
                            
                            <!-- Rarity Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="px-2 py-1 rounded-full text-xs font-bold
                                    @switch($achievement['rarity'])
                                        @case('common') bg-gray-100 text-gray-700 @break
                                        @case('uncommon') bg-green-100 text-green-700 @break
                                        @case('rare') bg-blue-100 text-blue-700 @break
                                        @case('legendary') bg-purple-100 text-purple-700 @break
                                    @endswitch">
                                    {{ ucfirst($achievement['rarity']) }}
                                </span>
                            </div>

                            <!-- Achievement Content -->
                            <div class="p-6">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-16 h-16 rounded-full flex items-center justify-center text-3xl
                                            @if($achievement['unlocked']) 
                                                bg-green-100 
                                            @else 
                                                bg-gray-100 grayscale opacity-50
                                            @endif">
                                            {{ $achievement['icon'] }}
                                        </div>
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h3 class="text-lg font-bold text-gray-900">{{ $achievement['name'] }}</h3>
                                            @if($achievement['unlocked'])
                                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        
                                        <p class="text-sm text-gray-600 mb-3">{{ $achievement['description'] }}</p>
                                        
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center text-xs">
                                                <span class="text-gray-500">{{ $achievement['requirement'] }}</span>
                                                <span class="font-medium text-blue-600">+{{ $achievement['points'] }} pts</span>
                                            </div>
                                            
                                            <!-- Progress Bar -->
                                            @if(!$achievement['unlocked'])
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" 
                                                         style="width: {{ $achievement['progress']['percentage'] }}%"></div>
                                                </div>
                                                <div class="flex justify-between text-xs text-gray-500">
                                                    <span>{{ $achievement['progress']['current'] }}/{{ $achievement['progress']['target'] }}</span>
                                                    <span>{{ round($achievement['progress']['percentage'], 1) }}%</span>
                                                </div>
                                            @else
                                                <div class="flex items-center text-xs text-green-600 font-medium">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    ¡Desbloqueado!
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>