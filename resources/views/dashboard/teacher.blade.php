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
                            <p class="text-indigo-100 text-sm font-medium">Notes Non Validées</p>
                            <p class="text-3xl font-bold mt-1">{{ $stats['pending_validation'] }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 shadow-lg shadow-emerald-500/25 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-emerald-100 text-sm font-medium">Notes Validées</p>
                            <p class="text-3xl font-bold mt-1">{{ $stats['validated'] }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl p-6 shadow-lg shadow-violet-500/25 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-violet-100 text-sm font-medium">Total Notes</p>
                            <p class="text-3xl font-bold mt-1">{{ $stats['total_notes'] }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-6 shadow-lg shadow-amber-500/25 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-amber-100 text-sm font-medium">Moyenne Générale</p>
                            <p class="text-3xl font-bold mt-1">{{ number_format($moyenne_generale, 1) }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-semibold text-slate-800">Notes en Attente de Validation</h3>
                    </div>
                    <div class="p-0">
                        <table class="w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Étudiant</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Matière</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Note</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($unvalidated_notes as $note)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-4 py-3 text-sm text-slate-700">
                                        {{ $note->etudiant->utilisateur->nom ?? '' }} {{ $note->etudiant->utilisateur->prenom ?? '' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-600">{{ $note->evaluation->matiere->libelle ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm font-bold {{ $note->note >= 10 ? 'text-emerald-600' : 'text-red-500' }}">
                                        {{ $note->note }}/20
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-sm text-slate-400">Aucune note en attente</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-semibold text-slate-800">Évaluations Récentes</h3>
                    </div>
                    <div class="p-0">
                        <table class="w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Type</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Matière</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Classe</th>
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
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-sm text-slate-400">Aucune évaluation</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-semibold text-slate-800 mb-4">Actions Rapides</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('evaluations.index') }}" class="flex items-center gap-3 p-4 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="text-sm font-medium text-slate-700">Mes Évaluations</span>
                        </a>
                        <a href="{{ route('notes.index') }}" class="flex items-center gap-3 p-4 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            </div>
                            <span class="text-sm font-medium text-slate-700">Saisir Notes</span>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-semibold text-slate-800 mb-4">Instructions</h3>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-indigo-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Créez des évaluations pour vos classes</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-indigo-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Saisissez les notes des étudiants</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-indigo-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Validez les notes avant de les partager</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
