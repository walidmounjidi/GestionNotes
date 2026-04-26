<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ __('Tableau de Bord Professeur') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Bienvenue, {{ Auth::user()->nom }} {{ Auth::user()->prenom }}</p>
            </div>
            <span class="text-sm text-slate-500">{{ now()->format('d/m/Y H:i') }}</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 shadow-lg shadow-indigo-500/25 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 text-sm font-medium">Classes Assignées</p>
                            <p class="text-3xl font-bold mt-1">{{ $stats['classes_count'] }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 shadow-lg shadow-emerald-500/25 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-emerald-100 text-sm font-medium">Matières</p>
                            <p class="text-3xl font-bold mt-1">{{ $stats['matieres_count'] }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253m0-13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332-.477-4.5-1.253"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl p-6 shadow-lg shadow-violet-500/25 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-violet-100 text-sm font-medium">Étudiants</p>
                            <p class="text-3xl font-bold mt-1">{{ $stats['students_count'] }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-semibold text-slate-800">Classes Assignées</h3>
                    </div>
                    <div class="space-y-3 p-4">
                        @forelse($assignedClasses as $classe)
                        <a href="{{ route('teacher.classe.detail', $classe->id) }}"
                           class="block border border-slate-200 rounded-lg p-4
                                  hover:border-indigo-300 hover:bg-indigo-50/30
                                  transition-colors cursor-pointer">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-slate-700">
                                        {{ $classe->libelle }}
                                    </h4>
                                    <span class="text-xs text-slate-500">
                                        {{ $classe->level->libelle ?? '' }}
                                        @if($classe->specialization)
                                         — {{ $classe->specialization->libelle }}
                                        @endif
                                    </span>
                                </div>
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full font-semibold text-sm">
                                    {{ $classe->etudiants->count() }}
                                </span>
                            </div>
                        </a>
                        @empty
                        <div class="text-center py-8 text-slate-400 text-sm">
                            Aucune classe assignée
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-semibold text-slate-800">Mes Matières</h3>
                    </div>
                    <div class="space-y-3 p-4">
                        @forelse($mySubjects as $subject)
                        <div class="border border-slate-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-slate-700">
                                        {{ $subject->libelle }}
                                    </h4>
                                    <span class="text-xs text-slate-500">
                                        Coefficient {{ $subject->coefficient }}
                                    </span>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-700">
                                    Coef. {{ $subject->coefficient }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-slate-400 text-sm">
                            Aucune matière assignée
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-semibold text-slate-800 mb-4">Évaluations à venir</h3>
                    @forelse($upcomingEvaluations as $eval)
                    <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0">
                        <div>
                            <span class="font-medium text-slate-700">{{ $eval->matiere->libelle ?? 'N/A' }}</span>
                            <span class="text-sm text-slate-500 block">{{ $eval->classe->libelle ?? 'N/A' }}</span>
                        </div>
                        <span class="text-sm text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                            {{ \Carbon\Carbon::parse($eval->date_evaluation)->format('d/m/Y') }}
                        </span>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400">Aucune évaluation à venir</p>
                    @endforelse
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-semibold text-slate-800 mb-4">Calendrier des Évaluations</h3>
                    @if(isset($evaluationsCalendar) && $evaluationsCalendar->count() > 0)
                        <div class="space-y-3">
                            @foreach($evaluationsCalendar as $date => $evaluations)
                                <div class="border-l-4 border-indigo-500 pl-4">
                                    <div class="text-sm font-semibold text-slate-700 mb-2">
                                        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                    </div>
                                    @foreach($evaluations as $eval)
                                        <div class="flex items-center justify-between py-2 bg-slate-50 rounded-lg px-3 mb-1">
                                            <span class="text-sm text-slate-600">{{ $eval->matiere->libelle ?? 'N/A' }}</span>
                                            <span class="text-xs text-slate-500">{{ $eval->classe->libelle ?? 'N/A' }}</span>
                                            <span class="px-2 py-0.5 text-xs rounded-full {{ $eval->type === 'examen' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                                {{ ucfirst($eval->type) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-400">Aucune évaluation prévue ce mois</p>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('evaluations.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors shadow-lg shadow-indigo-500/25">
                    <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Saisir une note
                </a>
            </div>
        </div>
    </div>
</x-app-layout>