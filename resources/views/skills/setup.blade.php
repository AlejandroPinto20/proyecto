<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Configuración de Habilidades') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Selecciona tus habilidades actuales para recibir recomendaciones personalizadas
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-white via-purple-50 to-pink-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('skills.store-setup') }}" id="skillsSetupForm">
                        @csrf

                        <!-- Progress Indicator -->
                        <div class="mb-8">
                            <div class="flex items-center justify-center space-x-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">1</div>
                                    <span class="ml-2 text-sm font-medium text-gray-900">Seleccionar</span>
                                </div>
                                <div class="w-16 h-1 bg-purple-300 dark:bg-purple-600 rounded"></div>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-pink-500 rounded-full flex items-center justify-center text-white font-bold">2</div>
                                    <span class="ml-2 text-sm font-medium text-gray-900">Niveles</span>
                                </div>
                                <div class="w-16 h-1 bg-purple-300 dark:bg-purple-600 rounded"></div>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-indigo-300 dark:bg-indigo-600 rounded-full flex items-center justify-center text-gray-900 dark:text-gray-200 font-bold">3</div>
                                    <span class="ml-2 text-sm font-medium text-gray-900">Confirmar</span>
                                </div>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 p-6 rounded-xl border border-purple-200 dark:border-purple-700 mb-8">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-lg">💡</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-purple-800 dark:text-purple-200 mb-2">¿Cómo funciona?</h3>
                                    <ul class="text-sm text-purple-700 dark:text-purple-300 space-y-1">
                                        <li>• Selecciona entre 3 y 10 habilidades que consideres que tienes</li>
                                        <li>• Indica tu nivel de experiencia en cada habilidad seleccionada</li>
                                        <li>• Recibirás recomendaciones personalizadas de actividades basadas en tus habilidades</li>
                                        <li>• El sistema aprenderá de tu evolución y mejorará las recomendaciones</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Skills Selection -->
                        @foreach($skillsByCategory as $category => $skills)
                            <div class="mb-8">
                                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                    <span class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                        {{ substr($category, 0, 1) }}
                                    </span>
                                    {{ ucfirst($category) }}
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($skills as $skill)
                                        <label class="relative cursor-pointer">
                                            <input type="checkbox" name="skills[]" value="{{ $skill->id }}" class="sr-only peer skill-checkbox" data-skill-id="{{ $skill->id }}">
                                            <div class="p-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-purple-400 dark:hover:border-purple-500 peer-checked:border-purple-500 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20 transition-all duration-200 shadow-sm hover:shadow-md">
                                                <div class="text-center">
                                                    <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                                        <span class="text-xl">{{ $skill->getEmoji() }}</span>
                                                    </div>
                                                    <h4 class="font-bold text-gray-800 dark:text-gray-200 mb-1">{{ $skill->name }}</h4>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $skill->description }}</p>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <!-- Level Selection (Hidden initially) -->
                        <div id="levelSelection" class="hidden bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 p-6 rounded-xl border border-indigo-200 dark:border-indigo-700">
                            <h3 class="text-xl font-bold text-indigo-800 dark:text-indigo-200 mb-6 flex items-center">
                                <span class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">2</span>
                                Indica tu Nivel de Experiencia
                            </h3>

                            <div id="selectedSkillsContainer" class="space-y-4">
                                <!-- Skills will be added here dynamically -->
                            </div>
                        </div>

                        <!-- Submit Section -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 p-6 rounded-xl border border-purple-200 dark:border-purple-700 mt-8">
                            <div class="text-center">
                                <div class="mb-6">
                                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-2">¡Listo para comenzar!</h3>
                                    <p class="text-gray-600 dark:text-gray-400">Configura tus habilidades y recibe recomendaciones personalizadas</p>
                                </div>
                                <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                        Saltar por ahora
                                    </a>
                                    <button type="submit" id="submitBtn" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white font-bold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        {{ __('Guardar Configuración') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const skillCheckboxes = document.querySelectorAll('.skill-checkbox');
            const levelSelection = document.getElementById('levelSelection');
            const selectedSkillsContainer = document.getElementById('selectedSkillsContainer');
            const submitBtn = document.getElementById('submitBtn');

            // Handle skill selection
            skillCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectedSkills();
                });
            });

            function updateSelectedSkills() {
                const selectedSkills = Array.from(skillCheckboxes).filter(cb => cb.checked);

                if (selectedSkills.length > 0) {
                    levelSelection.classList.remove('hidden');
                    selectedSkillsContainer.innerHTML = '';

                    selectedSkills.forEach(skillCheckbox => {
                        const skillId = skillCheckbox.value;
                        const skillLabel = skillCheckbox.closest('label');
                        const skillName = skillLabel.querySelector('h4').textContent;
                        const skillEmoji = skillLabel.querySelector('.w-12').textContent;

                        const skillDiv = document.createElement('div');
                        skillDiv.className = 'flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600';
                        skillDiv.innerHTML = `
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl">${skillEmoji}</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200">${skillName}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <label class="text-sm text-gray-600 dark:text-gray-400">Nivel:</label>
                                <select name="levels[${skillId}]" class="border border-gray-300 dark:border-gray-600 rounded px-2 py-1 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200" required>
                                    <option value="1">Principiante (1)</option>
                                    <option value="2">Básico (2)</option>
                                    <option value="3">Intermedio (3)</option>
                                    <option value="4">Avanzado (4)</option>
                                    <option value="5">Experto (5)</option>
                                </select>
                            </div>
                        `;

                        selectedSkillsContainer.appendChild(skillDiv);
                    });

                    // Enable submit button if between 3 and 10 skills selected
                    submitBtn.disabled = selectedSkills.length < 3 || selectedSkills.length > 10;
                } else {
                    levelSelection.classList.add('hidden');
                    submitBtn.disabled = true;
                }
            }

            // Form validation
            document.getElementById('skillsSetupForm').addEventListener('submit', function(e) {
                const selectedSkills = Array.from(skillCheckboxes).filter(cb => cb.checked);

                if (selectedSkills.length < 3) {
                    e.preventDefault();
                    alert('Por favor selecciona al menos 3 habilidades.');
                    return;
                }

                if (selectedSkills.length > 10) {
                    e.preventDefault();
                    alert('Por favor selecciona máximo 10 habilidades.');
                    return;
                }
            });

            // Add CSS animations
            const style = document.createElement('style');
            style.textContent = `
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                .animate-fade-in {
                    animation: fadeIn 0.3s ease-out;
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</x-app-layout>
