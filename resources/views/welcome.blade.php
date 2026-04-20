<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'ETU Note') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-900 to-slate-900">
            <nav class="border-b border-white/10 bg-white/5 backdrop-blur-lg">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="/dashboard" class="flex items-center gap-3">
                                <div class="bg-gradient-to-br from-indigo-500 to-violet-600 p-2.5 rounded-xl shadow-lg shadow-indigo-500/30">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <span class="text-white font-bold text-xl tracking-tight">ETU Note</span>
                            </a>
                        </div>
                        <div class="flex items-center gap-4">
                            @if (Route::has('login'))
                                @auth
                                    <a href="/dashboard" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white font-medium rounded-xl transition-all shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2.5 text-white/80 hover:text-white font-medium transition-colors">
                                        Connexion
                                    </a>
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </nav>

            <main class="flex items-center justify-center min-h-[calc(100vh-4rem)]">
                <div class="text-center px-4">
                    <div class="inline-flex items-center justify-center p-4 bg-gradient-to-br from-indigo-500/20 to-violet-500/20 rounded-2xl mb-8 border border-indigo-500/30">
                        <svg class="w-16 h-16 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 tracking-tight">
                        Gestion des <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-violet-400">Notes</span>
                    </h1>
                    <p class="text-xl text-white/60 mb-10 max-w-2xl mx-auto leading-relaxed">
                        Plateforme complète pour la gestion des étudiants, classes, matières, évaluations et notes. Simplifiez votre administration scolaire.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        @auth
                            <a href="/dashboard" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 text-lg">
                                Aller au Dashboard
                                <svg class="w-5 h-5 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 text-lg">
                               Se Connecter
                                <svg class="w-5 h-5 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        @endauth
                    </div>

                    <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 text-left hover:bg-white/10 transition-colors">
                            <div class="w-12 h-12 bg-indigo-500/20 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <h3 class="text-white font-semibold text-lg mb-2">Étudiants</h3>
                            <p class="text-white/50 text-sm">Gérez facilement les dossiers étudiants avec leur classe et leurs informations.</p>
                        </div>
                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 text-left hover:bg-white/10 transition-colors">
                            <div class="w-12 h-12 bg-violet-500/20 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <h3 class="text-white font-semibold text-lg mb-2">Matières</h3>
                            <p class="text-white/50 text-sm">Organisez vos matières par classe et suivre les programmes.</p>
                        </div>
                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 text-left hover:bg-white/10 transition-colors">
                            <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="text-white font-semibold text-lg mb-2">Notes & Évaluations</h3>
                            <p class="text-white/50 text-sm">Enregistrez et suivre les évaluations et les notes des étudiants.</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
