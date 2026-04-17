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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 shadow-lg shadow-indigo-500/25 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 text-sm font-medium">Mes Classes</p>
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
                            <p class="text-emerald-100 text-sm font-medium">Mes Étudiants</p>
                            <p class="text-3xl font-bold mt-1">{{ $stats['students_count'] }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl p-6 shadow-lg shadow-violet-500/25 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-violet-100 text-sm font-medium">Mes Matières</p>
                            <p class="text-3xl font-bold mt-1">{{ $stats['matieres_count'] }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-6 shadow-lg shadow-amber-500/25 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-amber-100 text-sm font-medium">Évaluations</p>
                            <p class="text-3xl font-bold mt-1">{{ $stats['total_evaluations'] }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            @if($assignedClasses->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-semibold text-slate-800">Saisie des Notes</h3>
                    <p class="text-sm text-slate-500 mt-1">Sélectionnez une évaluation pour saisir les notes des étudiants</p>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('notes.saisir', ['evaluation' => 'EVAL_ID']) }}" id="gradeForm" class="flex items-end gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Évaluation</label>
                            <select id="evaluationSelect" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Sélectionner une évaluation...</option>
                                @forelse($availableEvaluations as $eval)
                                <option value="{{ $eval->id }}">
                                    {{ $eval->classe->libelle ?? 'N/A' }} - {{ $eval->matiere->libelle ?? 'N/A' }} ({{ $eval->type }} - {{ $eval->date_evaluation->format('d/m/Y') }})
                                </option>
                                @empty
                                <option value="" disabled>Aucune évaluation disponible</option>
                                @endforelse
                            </select>
                        </div>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors shadow-sm">
                            Saisir les Notes
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-semibold text-slate-800">Mes Classes</h3>
                    </div>
                    <div class="p-0">
                        @forelse($assignedClasses as $classe)
                        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 last:border-b-0 hover:bg-slate-50/50 transition-colors">
                            <div>
                                <p class="font-medium text-slate-800">{{ $classe->libelle }}</p>
                                <p class="text-sm text-slate-500">{{ $classe->level->libelle ?? 'N/A' }} - {{ $classe->specialization->libelle ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-indigo-600">{{ $classe->etudiants->count() }}</p>
                                <p class="text-xs text-slate-500">étudiants</p>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-8 text-center text-sm text-slate-400">
                            Aucune classe assignée
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-semibold text-slate-800">Mes Matières</h3>
                    </div>
                    <div class="p-0">
                        @forelse($assignedMatieres as $mc)
                        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 last:border-b-0 hover:bg-slate-50/50 transition-colors">
                            <div>
                                <p class="font-medium text-slate-800">{{ $mc->matiere->libelle ?? 'N/A' }}</p>
                                <p class="text-sm text-slate-500">{{ $mc->classe->libelle ?? 'N/A' }}</p>
                            </div>
                            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-8 text-center text-sm text-slate-400">
                            Aucune matière assignée
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-semibold text-slate-800">Évaluations Récentes</h3>
                    </div>
                    <div class="p-0">
                        <table class="w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Matière</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Classe</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($recent_evaluations as $eval)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                            {{ $eval->type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $eval->matiere->libelle ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-600">{{ $eval->classe->libelle ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-500">{{ $eval->date_evaluation->format('d/m/Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-400">Aucune évaluation</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-semibold text-slate-800">Actions Rapides</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('evaluations.create') }}" class="flex items-center gap-3 p-4 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700">Nouvelle Évaluation</span>
                            </a>
                            <a href="{{ route('evaluations.index') }}" class="flex items-center gap-3 p-4 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors">
                                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700">Toutes les Évaluations</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('gradeForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const select = document.getElementById('evaluationSelect');
            if (select.value) {
                const action = this.action.replace('EVAL_ID', select.value);
                window.location.href = action;
            }
        });
    </script>
</x-app-layout>
