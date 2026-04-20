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

            <!-- Assigned Classes and Subjects -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-semibold text-slate-800">Classes Assignées</h3>
                    </div>
                    <div class="space-y-4">
                        @forelse($assignedClasses as $classe)
                        <div class="border border-slate-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-medium text-slate-700">{{ $classe->libelle }}</h4>
                                <span class="text-sm text-slate-500">{{ $classe->level->libelle ?? '' }} - {{ $classe->specialization->libelle ?? '' }}</span>
                            </div>
                            <div class="text-sm text-slate-600">
                                <span>{{ $classe->etudiants->count() }} étudiants</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-slate-400">
                            Aucune classe assignée
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-semibold text-slate-800">Matières Assignées</h3>
                    </div>
                    <div class="space-y-3">
                        @forelse($assignedMatieres as $matiere)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                            <div>
                                <div class="font-medium text-slate-700">{{ $matiere->matiere->libelle }}</div>
                                <div class="text-sm text-slate-500">{{ $matiere->classe->libelle }}</div>
                            </div>
                            <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full">Assignée</span>
                        </div>
                        @empty
                        <div class="text-center py-8 text-slate-400">
                            Aucune matière assignée
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Students and Grades Management -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-semibold text-slate-800">Gestion des Notes</h3>
                </div>
                <div class="p-0">
                    @forelse($studentsByClass as $classData)
                    <div class="mb-8">
                        <h4 class="font-medium text-slate-700 mb-4">{{ $classData['classe']->libelle }}</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Étudiant</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Matières</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Notes</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Moyenne</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($classData['students'] as $studentData)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-4 py-3 text-sm text-slate-700">
                                            {{ $studentData['etudiant']->utilisateur->nom }} {{ $studentData['etudiant']->utilisateur->prenom }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-600">
                                            @foreach($studentData['notes'] as $note)
                                                <span class="inline-block px-2 py-1 text-xs bg-slate-100 rounded mr-1 mb-1">
                                                    {{ $note->evaluation->matiere->libelle }}: {{ $note->note }}/20
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="px-4 py-3 text-sm text-center">
                                            @if($studentData['moyenne'])
                                                <span class="font-bold {{ $studentData['moyenne'] >= 10 ? 'text-emerald-600' : 'text-red-500' }}">
                                                    {{ number_format($studentData['moyenne'], 2) }}/20
                                                </span>
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-400">Aucun étudiant dans cette classe</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
