<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Detalles de Habilidad') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Skill Info -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center">
                                    <span class="text-4xl mr-4">{{ $skill->getEmoji() }}</span>
                                    <div>
                                        <h3 class="text-2xl font-bold text-white">{{ $skill->name }}</h3>
                                        <p class="text-gray-400">{{ $skill->slug }}</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.skills.edit', $skill) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('admin.skills.destroy', $skill) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200" onclick="return confirm('¿Estás seguro de eliminar esta habilidad?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <h4 class="text-lg font-semibold text-white mb-2">Información General</h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-400">Categoría:</span>
                                            <span class="text-white capitalize">{{ $skill->category }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-400">Estado:</span>
                                            <span class="text-white">{{ $skill->is_active ? 'Activa' : 'Inactiva' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-400">Creada:</span>
                                            <span class="text-white">{{ $skill->created_at->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-400">Actualizada:</span>
                                            <span class="text-white">{{ $skill->updated_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-lg font-semibold text-white mb-2">Estadísticas</h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-400">Usuarios con esta habilidad:</span>
                                            <span class="text-white">{{ $usersWithSkill->count() }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-400">Actividades que la desarrollan:</span>
                                            <span class="text-white">{{ $activities->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($skill->description)
                                <div>
                                    <h4 class="text-lg font-semibold text-white mb-2">Descripción</h4>
                                    <p class="text-gray-300">{{ $skill->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="space-y-6">
                    <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-white mb-4">Usuarios con esta Habilidad</h4>

                            @if($usersWithSkill->count() > 0)
                                <div class="space-y-3">
                                    @foreach($usersWithSkill->take(5) as $user)
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                @if($user->profile_photo_path)
                                                    <img class="h-8 w-8 rounded-full mr-3" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}">
                                                @else
                                                    <div class="h-8 w-8 rounded-full bg-gray-600 flex items-center justify-center mr-3">
                                                        <span class="text-sm text-white">{{ substr($user->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="text-sm font-medium text-white">{{ $user->name }}</p>
                                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                            <span class="text-sm text-blue-400">Nivel {{ $user->pivot->level }}</span>
                                        </div>
                                    @endforeach

                                    @if($usersWithSkill->count() > 5)
                                        <p class="text-sm text-gray-400 text-center mt-3">
                                            Y {{ $usersWithSkill->count() - 5 }} usuarios más...
                                        </p>
                                    @endif
                                </div>
                            @else
                                <p class="text-gray-400 text-center">Ningún usuario tiene esta habilidad aún.</p>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-white mb-4">Actividades Relacionadas</h4>

                            @if($activities->count() > 0)
                                <div class="space-y-3">
                                    @foreach($activities->take(5) as $activity)
                                        <div class="p-3 bg-gray-700 rounded-lg">
                                            <h5 class="text-sm font-medium text-white">{{ $activity->name }}</h5>
                                            <p class="text-xs text-gray-400 mt-1">{{ Str::limit($activity->description, 60) }}</p>
                                            <div class="flex justify-between items-center mt-2">
                                                <span class="text-xs text-purple-400">Peso: {{ $activity->pivot->development_weight }}</span>
                                                <span class="text-xs text-gray-500">{{ $activity->start_date?->format('d/m') }}</span>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($activities->count() > 5)
                                        <p class="text-sm text-gray-400 text-center mt-3">
                                            Y {{ $activities->count() - 5 }} actividades más...
                                        </p>
                                    @endif
                                </div>
                            @else
                                <p class="text-gray-400 text-center">Ninguna actividad desarrolla esta habilidad aún.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-8 text-center">
                <a href="{{ route('admin.skills.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200">
                    ← Volver a Habilidades
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
