<nav x-data="{ open: false, sidebarOpen: false }" class="relative bg-gradient-to-r from-slate-900 via-blue-900 to-purple-900 shadow-2xl backdrop-blur-lg border-b border-white/10 z-30">
    <!-- Sidebar Overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm" x-transition:enter="opacity-0" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="opacity-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="transition: opacity 0.3s ease-in-out;"></div>

    <!-- Sidebar -->
    <div x-show="sidebarOpen" class="fixed inset-y-0 left-0 z-50 w-80 bg-slate-900 shadow-2xl border-r border-slate-700" x-transition:enter="transform -translate-x-full" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform translate-x-0" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" style="transition: transform 0.3s ease-in-out; background: linear-gradient(180deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);">
        <div class="flex items-center justify-between p-6 border-b border-slate-700/50 bg-gradient-to-r from-blue-600/20 to-purple-600/20">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-white font-bold text-xl tracking-wide">UNIFRANZ</span>
                    <div class="text-slate-300 text-sm font-medium">Sistema de Voluntariado</div>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white transition-all duration-200 p-2 rounded-xl hover:bg-white/10 hover:scale-110">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="py-6 px-4">
            <!-- Navegación Principal -->
            <div class="mb-8">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4 px-4">Navegación Principal</h3>
                <div class="space-y-2">
                    <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" class="group flex items-center px-4 py-3 text-black bg-white/90 hover:text-black hover:bg-white rounded-2xl transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('dashboard') ? 'bg-white text-black shadow-xl scale-105' : '' }}">
                        <div class="flex items-center space-x-4">
                            <div class="p-2.5 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-200 text-black group-hover:bg-blue-600 group-hover:text-white' }} transition-all duration-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-sm">{{ __('Dashboard') }}</span>
                        </div>
                    </a>


                    <a href="{{ route('rankings.index') }}" @click="sidebarOpen = false" class="group flex items-center px-4 py-3 text-black bg-white/90 hover:text-black hover:bg-white rounded-2xl transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('rankings.*') ? 'bg-white text-black shadow-xl scale-105' : '' }}">
                        <div class="flex items-center space-x-4">
                            <div class="p-2.5 rounded-xl {{ request()->routeIs('rankings.*') ? 'bg-emerald-600 text-white shadow-lg' : 'bg-gray-200 text-black group-hover:bg-emerald-600 group-hover:text-white' }} transition-all duration-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-sm">{{ __('Rankings') }}</span>
                        </div>
                    </a>

                    <a href="{{ route('donations.index') }}" @click="sidebarOpen = false" class="group flex items-center px-4 py-3 text-black bg-white/90 hover:text-black hover:bg-white rounded-2xl transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('donations.*') ? 'bg-white text-black shadow-xl scale-105' : '' }}">
                        <div class="flex items-center space-x-4">
                            <div class="p-2.5 rounded-xl {{ request()->routeIs('donations.*') ? 'bg-orange-600 text-white shadow-lg' : 'bg-gray-200 text-black group-hover:bg-orange-600 group-hover:text-white' }} transition-all duration-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-sm">{{ __('Donaciones') }}</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Herramientas Personales -->
            <div class="mb-8">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4 px-4">Herramientas</h3>
                <div class="space-y-2">

                    <a href="{{ route('activities.index') }}" @click="sidebarOpen = false" class="group flex items-center px-4 py-3 text-black bg-white/90 hover:text-black hover:bg-white rounded-2xl transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('activities.*') || request()->routeIs('calendar.*') ? 'bg-white text-black shadow-xl scale-105' : '' }}">
                        <div class="flex items-center space-x-4">
                            <div class="p-2.5 rounded-xl {{ request()->routeIs('activities.*') || request()->routeIs('calendar.*') ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-200 text-black group-hover:bg-indigo-600 group-hover:text-white' }} transition-all duration-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-sm">{{ __('Actividades') }}</span>
                        </div>
                    </a>

                    <a href="{{ route('certificates.index') }}" @click="sidebarOpen = false" class="group flex items-center px-4 py-3 text-black bg-white/90 hover:text-black hover:bg-white rounded-2xl transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('certificates.*') ? 'bg-white text-black shadow-xl scale-105' : '' }}">
                        <div class="flex items-center space-x-4">
                            <div class="p-2.5 rounded-xl {{ request()->routeIs('certificates.*') ? 'bg-teal-600 text-white shadow-lg' : 'bg-gray-200 text-black group-hover:bg-teal-600 group-hover:text-white' }} transition-all duration-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-sm">{{ __('Certificados') }}</span>
                        </div>
                    </a>

                    <a href="{{ route('notifications.index') }}" @click="sidebarOpen = false" class="group flex items-center px-4 py-3 text-black bg-white/90 hover:text-black hover:bg-white rounded-2xl transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('notifications.*') ? 'bg-white text-black shadow-xl scale-105' : '' }}">
                        <div class="flex items-center space-x-4">
                            <div class="p-2.5 rounded-xl {{ request()->routeIs('notifications.*') ? 'bg-pink-600 text-white shadow-lg' : 'bg-gray-200 text-black group-hover:bg-pink-600 group-hover:text-white' }} transition-all duration-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h4l4 4v-4h4a2 2 0 002-2z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-sm">{{ __('Notificaciones') }}</span>
                        </div>
                    </a>

                    @if(!auth()->user()->isAdmin())
                    <a href="{{ route('markov.insights') }}" @click="sidebarOpen = false" class="group flex items-center px-4 py-3 text-black bg-white/90 hover:text-black hover:bg-white rounded-2xl transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('markov.*') ? 'bg-white text-black shadow-xl scale-105' : '' }}">
                        <div class="flex items-center space-x-4">
                            <div class="p-2.5 rounded-xl {{ request()->routeIs('markov.*') ? 'bg-violet-600 text-white shadow-lg' : 'bg-gray-200 text-black group-hover:bg-violet-600 group-hover:text-white' }} transition-all duration-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-sm">{{ __('Recomendaciones') }}</span>
                        </div>
                    </a>

                    <a href="{{ route('achievements.index') }}" @click="sidebarOpen = false" class="group flex items-center px-4 py-3 text-black bg-white/90 hover:text-black hover:bg-white rounded-2xl transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('achievements.*') ? 'bg-white text-black shadow-xl scale-105' : '' }}">
                        <div class="flex items-center space-x-4">
                            <div class="p-2.5 rounded-xl {{ request()->routeIs('achievements.*') ? 'bg-amber-600 text-white shadow-lg' : 'bg-gray-200 text-black group-hover:bg-amber-600 group-hover:text-white' }} transition-all duration-300">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-sm">{{ __('Mis Logros') }}</span>
                        </div>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Administración -->
            @if(Auth::user()->isAdmin())
            <div class="mb-8">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4 px-4">Administración</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" @click="sidebarOpen = false" class="group flex items-center px-4 py-3 text-black bg-white/90 hover:text-black hover:bg-white rounded-2xl transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.dashboard') ? 'bg-white text-black shadow-xl scale-105' : '' }}">
                        <div class="flex items-center space-x-4">
                            <div class="p-2.5 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white shadow-lg' : 'bg-gray-200 text-black group-hover:bg-red-600 group-hover:text-white' }} transition-all duration-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-sm">{{ __('Panel Admin') }}</span>
                        </div>
                    </a>
                </div>
            </div>
            @endif

            <!-- User Section -->
            <div class="border-t border-slate-700/50 mt-6 pt-6">
                <div class="px-4 mb-6">
                    <div class="bg-gradient-to-br from-slate-800/80 to-slate-700/80 rounded-3xl p-5 backdrop-blur-sm border border-slate-600/30 shadow-2xl">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="relative">
                                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <span class="text-white font-bold text-xl">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-slate-800"></div>
                            </div>
                            <div class="flex-1">
                                <div class="text-white font-bold text-lg">{{ Auth::user()->name }}</div>
                                <div class="text-slate-300 text-sm">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gradient-to-br from-blue-600/20 to-blue-700/20 rounded-2xl p-3 text-center border border-blue-500/20">
                                <div class="text-blue-400 font-bold text-lg">{{ Auth::user()->points ?? 0 }}</div>
                                <div class="text-slate-300 text-xs font-medium">Puntos</div>
                            </div>
                            <div class="bg-gradient-to-br from-purple-600/20 to-purple-700/20 rounded-2xl p-3 text-center border border-purple-500/20">
                                <div class="text-purple-400 font-bold text-lg">{{ Auth::user()->getUnlockedAchievementsCount() }}</div>
                                <div class="text-slate-300 text-xs font-medium">Logros</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-4 space-y-2">
                    <a href="{{ route('profile.show') }}" @click="sidebarOpen = false" class="group flex items-center px-4 py-3 text-black bg-white/90 hover:text-black hover:bg-white rounded-2xl transition-all duration-300 transform hover:scale-105">
                        <div class="flex items-center space-x-4">
                            <div class="p-2.5 rounded-xl bg-gray-200 text-black group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-sm">Mi Perfil</span>
                        </div>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" @click="sidebarOpen = false" class="group w-full flex items-center px-4 py-3 text-black bg-white/90 hover:text-red-600 hover:bg-white rounded-2xl transition-all duration-300 transform hover:scale-105">
                            <div class="flex items-center space-x-4">
                                <div class="p-2.5 rounded-xl bg-gray-200 text-black group-hover:bg-red-600 group-hover:text-white transition-all duration-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </div>
                                <span class="font-semibold text-sm">{{ __('Cerrar Sesión') }}</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-18">
            <div class="flex items-center">
                <!-- Hamburger Menu Button -->
                <div class="flex items-center mr-6">
                    <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center justify-center p-3 rounded-2xl text-white/90 hover:text-white hover:bg-white/10 focus:outline-none focus:bg-white/20 transition-all duration-300 transform hover:scale-110 backdrop-blur-sm">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': sidebarOpen, 'inline-flex': ! sidebarOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! sidebarOpen, 'inline-flex': sidebarOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-20 h-20 bg-gradient-to-br from-white/20 to-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/20 group-hover:scale-110 transition-transform duration-300">
                            <x-application-logo class="block h-16 w-auto fill-current text-white" />
                        </div>
                        <div>
                            <span class="text-white font-bold text-xl tracking-wide">UNIFRANZ</span>
                            <div class="text-white/70 text-xs font-medium">Voluntariado</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden lg:flex items-center space-x-2 ml-8">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="px-4 py-2 rounded-xl text-white/90 hover:text-white hover:bg-white/10 transition-all duration-300 font-medium {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : '' }}">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('activities.index')" :active="request()->routeIs('activities.*') || request()->routeIs('calendar.*')" class="px-4 py-2 rounded-xl text-white/90 hover:text-white hover:bg-white/10 transition-all duration-300 font-medium {{ request()->routeIs('activities.*') || request()->routeIs('calendar.*') ? 'bg-white/20 text-white' : '' }}">
                        {{ __('Actividades') }}
                    </x-nav-link>

                    <x-nav-link :href="route('rankings.index')" :active="request()->routeIs('rankings.*')" class="px-4 py-2 rounded-xl text-white/90 hover:text-white hover:bg-white/10 transition-all duration-300 font-medium {{ request()->routeIs('rankings.*') ? 'bg-white/20 text-white' : '' }}">
                        {{ __('Rankings') }}
                    </x-nav-link>

                    <x-nav-link :href="route('donations.index')" :active="request()->routeIs('donations.*')" class="px-4 py-2 rounded-xl text-white/90 hover:text-white hover:bg-white/10 transition-all duration-300 font-medium {{ request()->routeIs('donations.*') ? 'bg-white/20 text-white' : '' }}">
                        {{ __('Donaciones') }}
                    </x-nav-link>

                    @if(Auth::user()->isAdmin())
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="px-4 py-2 rounded-xl text-white/90 hover:text-white hover:bg-white/10 transition-all duration-300 font-medium {{ request()->routeIs('admin.*') ? 'bg-white/20 text-white' : '' }}">
                        {{ __('Admin') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Notifications & Settings -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-3">
                <!-- Notifications Dropdown -->
                <x-dropdown align="right" width="80">
                    <x-slot name="trigger">
                        <button class="relative inline-flex items-center p-3 text-white/90 hover:text-white hover:bg-white/10 focus:outline-none transition-all duration-300 rounded-2xl backdrop-blur-sm transform hover:scale-110">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h4l4 4v-4h4a2 2 0 002-2z"></path>
                            </svg>
                            <span id="notificationBadge" class="absolute -top-1 -right-1 h-5 w-5 bg-gradient-to-r from-red-500 to-pink-500 rounded-full text-xs text-white flex items-center justify-center hidden shadow-lg">0</span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h3 class="text-sm font-medium text-gray-900">Notificaciones</h3>
                        </div>
                        <div id="notificationsList" class="max-h-64 overflow-y-auto">
                            <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                Cargando notificaciones...
                            </div>
                        </div>
                        <div class="px-4 py-2 border-t border-gray-200">
                            <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Ver todas las notificaciones</a>
                        </div>
                    </x-slot>
                </x-dropdown>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2.5 text-white/90 hover:text-white hover:bg-white/10 focus:outline-none transition-all duration-300 rounded-2xl backdrop-blur-sm font-medium transform hover:scale-105">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                                <div class="hidden md:block text-left">
                                    <div class="text-sm font-semibold">{{ Auth::user()->name }}</div>
                                </div>
                            </div>
                            <div class="ml-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.show')">
                            Mi Perfil de Usuario
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Menu Button -->
            <div class="flex items-center sm:hidden space-x-2">
                <!-- Mobile Notifications -->
                <button class="relative inline-flex items-center p-2 text-white/90 hover:text-white hover:bg-white/10 focus:outline-none transition-all duration-300 rounded-xl">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h4l4 4v-4h4a2 2 0 002-2z"></path>
                    </svg>
                    <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center hidden">0</span>
                </button>
                
                <!-- Mobile Hamburger -->
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-white/90 hover:text-white hover:bg-white/10 focus:outline-none transition-all duration-300">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-900/95 backdrop-blur-lg border-t border-white/10">
        <div class="pt-4 pb-3 space-y-2 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white/90 hover:text-white hover:bg-white/10 rounded-xl font-medium {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : '' }}">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('activities.index')" :active="request()->routeIs('activities.*') || request()->routeIs('calendar.*')" class="text-white/90 hover:text-white hover:bg-white/10 rounded-xl font-medium {{ request()->routeIs('activities.*') || request()->routeIs('calendar.*') ? 'bg-white/20 text-white' : '' }}">
                {{ __('Actividades') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('rankings.index')" :active="request()->routeIs('rankings.*')" class="text-white/90 hover:text-white hover:bg-white/10 rounded-xl font-medium {{ request()->routeIs('rankings.*') ? 'bg-white/20 text-white' : '' }}">
                {{ __('Rankings') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('donations.index')" :active="request()->routeIs('donations.*')" class="text-white/90 hover:text-white hover:bg-white/10 rounded-xl font-medium {{ request()->routeIs('donations.*') ? 'bg-white/20 text-white' : '' }}">
                {{ __('Donaciones') }}
            </x-responsive-nav-link>

            @if(Auth::user()->isAdmin())
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-white/90 hover:text-white hover:bg-white/10 rounded-xl font-medium {{ request()->routeIs('admin.*') ? 'bg-white/20 text-white' : '' }}">
                {{ __('Admin') }}
            </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-4 border-t border-white/10 mx-4">
            <div class="bg-gradient-to-br from-slate-800/80 to-slate-700/80 rounded-2xl p-4 mb-4 backdrop-blur-sm border border-slate-600/30">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-lg">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <div class="font-semibold text-white">{{ Auth::user()->name }}</div>
                        <div class="text-sm text-slate-300">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="bg-slate-700/50 rounded-xl p-2 text-center">
                        <div class="text-blue-400 font-bold">{{ Auth::user()->points ?? 0 }}</div>
                        <div class="text-slate-300">Puntos</div>
                    </div>
                    <div class="bg-slate-700/50 rounded-xl p-2 text-center">
                        <div class="text-purple-400 font-bold">{{ Auth::user()->getUnlockedAchievementsCount() }}</div>
                        <div class="text-slate-300">Logros</div>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <x-responsive-nav-link :href="route('profile.show')" class="text-white/90 hover:text-white hover:bg-white/10 rounded-xl font-medium">
                    Mi Perfil de Usuario
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-400 hover:text-red-300 hover:bg-red-900/20 rounded-xl font-medium">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
