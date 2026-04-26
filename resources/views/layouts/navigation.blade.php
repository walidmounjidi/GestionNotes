<nav x-data="{ open: false }" class="bg-gradient-to-r from-indigo-600 to-violet-600 dark:from-indigo-800 dark:to-violet-800 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <div class="bg-white/20 backdrop-blur-sm p-2 rounded-lg">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-white font-bold text-xl tracking-tight">ETU Note</span>
                    </a>
                </div>

                <div class="hidden space-x-1 sm:ms-8 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="!text-white/80 hover:!text-white hover:bg-white/10">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(Auth::user()->hasRole(['admin']))
                    <x-nav-link :href="route('classes.index')" :active="request()->routeIs('classes.*')" class="!text-white/80 hover:!text-white hover:bg-white/10">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        {{ __('Classes') }}
                    </x-nav-link>
                    <x-nav-link :href="route('matieres.index')" :active="request()->routeIs('matieres.*')" class="!text-white/80 hover:!text-white hover:bg-white/10">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        {{ __('Matières') }}
                    </x-nav-link>
<x-nav-link :href="route('teacher.index')"
                                      :active="request()->routeIs('teacher.*')"
                                      class="!text-white/80 hover:!text-white hover:bg-white/10">
                          <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                          {{ __('Professeurs') }}
                      </x-nav-link>
                    <x-nav-link :href="route('etudiants.index')" :active="request()->routeIs('etudiants.*')" class="!text-white/80 hover:!text-white hover:bg-white/10">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        {{ __('Étudiants') }}
                    </x-nav-link>
                    @if(Auth::user()->hasRole(['admin']))
                    <x-nav-link :href="route('evaluations.index')" :active="request()->routeIs('evaluations.*')" class="!text-white/80 hover:!text-white hover:bg-white/10">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        {{ __('Évaluations') }}
                    </x-nav-link>
                    @endif
                    @endif

                    @if(Auth::user()->isTeacher())
                    <x-nav-link :href="route('dashboard.teacher')" :active="request()->routeIs('dashboard.teacher')" class="!text-white/80 hover:!text-white hover:bg-white/10">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        {{ __('Tableau de Bord') }}
                    </x-nav-link>
                    <x-nav-link :href="route('evaluations.index')" :active="request()->routeIs('evaluations.*')" class="!text-white/80 hover:!text-white hover:bg-white/10">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        {{ __('Évaluations') }}
                    </x-nav-link>
                    <x-nav-link :href="route('notes.index')" :active="request()->routeIs('notes.*')" class="!text-white/80 hover:!text-white hover:bg-white/10">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        {{ __('Mes Notes') }}
                    </x-nav-link>
                    @endif

                    @if(Auth::user()->isStudent())
                    <x-nav-link :href="route('dashboard.student')" :active="request()->routeIs('dashboard.student')" class="!text-white/80 hover:!text-white hover:bg-white/10">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        {{ __('Mes Notes') }}
                    </x-nav-link>
                    @endif

                    @if(Auth::user()->isAdmin())
                    <x-nav-link :href="route('utilisateurs.index')" :active="request()->routeIs('utilisateurs.*')" class="!text-white/80 hover:!text-white hover:bg-white/10">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        {{ __('Utilisateurs') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-white/30 text-sm leading-4 font-medium rounded-full text-white bg-white/10 hover:bg-white/20 focus:outline-none transition ease-in-out duration-150 backdrop-blur-sm">
                            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center me-2">
                                <span class="text-sm font-semibold">{{ substr(Auth::user()->prenom ?? 'U', 0, 1) }}{{ substr(Auth::user()->nom ?? 'ser', 0, 1) }}</span>
                            </div>
                            <div>{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white/70 hover:text-white hover:bg-white/10 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1 bg-white/10">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="!text-white/80 hover:!text-white hover:!bg-white/10">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(Auth::user()->hasRole(['admin']))
            <x-responsive-nav-link :href="route('classes.index')" :active="request()->routeIs('classes.*')" class="!text-white/80 hover:!text-white hover:!bg-white/10">
                {{ __('Classes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('matieres.index')" :active="request()->routeIs('matieres.*')" class="!text-white/80 hover:!text-white hover:!bg-white/10">
                {{ __('Matières') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('teacher.index')"
                                         :active="request()->routeIs('teacher.*')"
                                         class="!text-white/80 hover:!text-white hover:!bg-white/10">
                {{ __('Professeurs') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('etudiants.index')" :active="request()->routeIs('etudiants.*')" class="!text-white/80 hover:!text-white hover:!bg-white/10">
                {{ __('Étudiants') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('evaluations.index')" :active="request()->routeIs('evaluations.*')" class="!text-white/80 hover:!text-white hover:!bg-white/10">
                {{ __('Évaluations') }}
            </x-responsive-nav-link>
            @endif

            @if(Auth::user()->isTeacher())
            <x-responsive-nav-link :href="route('evaluations.index')" :active="request()->routeIs('evaluations.*')" class="!text-white/80 hover:!text-white hover:!bg-white/10">
                {{ __('Évaluations') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('notes.index')" :active="request()->routeIs('notes.*')" class="!text-white/80 hover:!text-white hover:!bg-white/10">
                {{ __('Notes') }}
            </x-responsive-nav-link>
            @endif

            @if(Auth::user()->isAdmin())
            <x-responsive-nav-link :href="route('utilisateurs.index')" :active="request()->routeIs('utilisateurs.*')" class="!text-white/80 hover:!text-white hover:!bg-white/10">
                {{ __('Utilisateurs') }}
            </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-white/20">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</div>
                <div class="font-medium text-sm text-white/70">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="!text-white/80 hover:!text-white hover:!bg-white/10">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="!text-white/80 hover:!text-white hover:!bg-white/10">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
