<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ __('Mon Tableau de Bord') }}
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
                            <p class="text-indigo-100 text-sm font-medium">Ma Moyenne</p>
                            <p class="text-3xl font-bold mt-1">{{ number_format($moyenne_generale ?? 0, 2) }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                    </div>
                    <p class="text-xs text-indigo-100 mt-2">sur 20</p>
                </div>

                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 shadow-lg shadow-emerald-500/25 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-emerald-100 text-sm font-medium">Mon Matricule</p>
                            <p class="text-xl font-bold mt-1">{{ $etudiant->matricule }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                        </div>
                    </div>
                    <p class="text-xs text-emerald-100 mt-2">Classe: {{ $etudiant->classe->libelle ?? 'Non assigné' }}</p>
                </div>

                @if($ranking)
                <div class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl p-6 shadow-lg shadow-violet-500/25 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-violet-100 text-sm font-medium">Ma Classement</p>
                            <p class="text-3xl font-bold mt-1">{{ $ranking['position'] }}/{{ $ranking['total'] }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </div>
                    </div>
                    <p class="text-xs text-violet-100 mt-2">dans votre classe</p>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="font-semibold text-slate-800">Mes Dernières Notes</h3>
                        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700">Voir tout</a>
                    </div>
                    <div class="p-0">
                        <table class="w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Matière</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Type</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Note</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($recent_notes as $note)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $note->evaluation->matiere->libelle ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                            {{ $note->evaluation->type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-bold {{ $note->note >= 10 ? 'text-emerald-600' : 'text-red-500' }}">
                                        {{ $note->note }}/20
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-sm text-slate-400">Aucune note disponible</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-semibold text-slate-800">Évaluations à Venir</h3>
                    </div>
                    <div class="p-0">
                        @forelse($upcoming_evaluations as $eval)
                        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 last:border-b-0 hover:bg-slate-50/50 transition-colors">
                            <div>
                                <p class="font-medium text-slate-800">{{ $eval->matiere->libelle ?? 'N/A' }}</p>
                                <p class="text-sm text-slate-500">{{ $eval->type }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-slate-800">{{ $eval->date_evaluation->format('d/m/Y') }}</p>
                                <p class="text-sm text-slate-500">{{ $eval->classe->libelle ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-8 text-center text-sm text-slate-400">
                            Aucune évaluation à venir
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            @if($studentSubjects->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-semibold text-slate-800">Mes Matières</h3>
                    <p class="text-sm text-slate-500 mt-1">Matières enseignées dans ma classe avec mes moyennes</p>
                </div>
                <div class="p-0">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Matière</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Coefficient</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Notes</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Moyenne</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($studentSubjects as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700">{{ $item['matiere']->libelle }}</td>
                                <td class="px-4 py-3 text-sm text-center text-slate-600">{{ $item['matiere']->coefficient }}</td>
                                <td class="px-4 py-3 text-sm text-center text-slate-600">{{ $item['notes_count'] }}</td>
                                <td class="px-4 py-3 text-sm text-right">
                                    @if($item['average'] !== null)
                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $item['average'] >= 10 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $item['average'] }}/20
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-sm">Pas de notes</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-400">Aucune matière trouvée</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($notes_by_matiere->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
                <h3 class="font-semibold text-slate-800 mb-4">Détails des Notes par Matière</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($notes_by_matiere as $matiere => $stats)
                    <div class="bg-slate-50 rounded-xl p-4">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-medium text-slate-800">{{ $matiere }}</span>
                            <span class="text-xs text-slate-500">{{ $stats['count'] }} notes</span>
                        </div>
                        <div class="flex items-end gap-4">
                            <span class="text-2xl font-bold {{ $stats['avg'] >= 10 ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $stats['avg'] }}
                            </span>
                            <span class="text-xs text-slate-500">moyenne</span>
                        </div>
                        <div class="flex justify-between items-center mt-2 text-xs text-slate-500">
                            <span>Min: {{ $stats['min'] }}</span>
                            <span>Max: {{ $stats['max'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
