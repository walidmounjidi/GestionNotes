<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ __('Mon Tableau de Bord') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Bienvenue, {{ Auth::user()->nom }} {{ Auth::user()->prenom }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 text-white">
                    <p class="text-indigo-100 text-sm font-medium">Ma Moyenne</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($moyenne ?? 0, 2) }}/20</p>
                </div>
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white">
                    <p class="text-emerald-100 text-sm font-medium">Ma Classe</p>
                    <p class="text-xl font-bold mt-1">{{ $etudiant->classe->libelle ?? 'Non assigné' }}</p>
                </div>
                <div class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl p-6 text-white">
                    <p class="text-violet-100 text-sm font-medium">Camarades</p>
                    <p class="text-3xl font-bold mt-1">{{ $classmates->count() }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                        <h3 class="font-semibold text-slate-800">Mes Dernières Notes</h3>
                        <a href="{{ route('student.grades') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Voir tout</a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($notes->take(5) as $note)
                        <div class="px-6 py-4 flex justify-between items-center">
                            <div>
                                <p class="text-slate-800 font-medium">{{ $note->evaluation->matiere->libelle ?? 'N/A' }}</p>
                                <p class="text-xs text-slate-500">{{ $note->evaluation->type }}</p>
                            </div>
                            <span class="text-lg font-bold {{ $note->note >= 10 ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $note->note }}/20
                            </span>
                        </div>
                        @empty
                        <div class="px-6 py-8 text-center text-slate-500">Aucune note</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                        <h3 class="font-semibold text-slate-800">Évaluations à Venir</h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($upcomingEvaluations as $eval)
                        <div class="px-6 py-4 flex justify-between items-center">
                            <div>
                                <p class="text-slate-800 font-medium">{{ $eval->matiere->libelle ?? 'N/A' }}</p>
                                <p class="text-xs text-slate-500">{{ $eval->type }}</p>
                            </div>
                            <span class="text-sm text-slate-600">{{ $eval->date_evaluation->format('d/m/Y') }}</span>
                        </div>
                        @empty
                        <div class="px-6 py-8 text-center text-slate-500">Aucune évaluation à venir</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <a href="{{ route('student.grades') }}" class="flex-1 px-6 py-4 bg-indigo-50 text-indigo-700 rounded-xl text-center font-medium hover:bg-indigo-100 transition-colors">
                    📊 Voir mes notes
                </a>
                @if($etudiant->classe_id)
                <a href="{{ route('student.classmates') }}" class="flex-1 px-6 py-4 bg-emerald-50 text-emerald-700 rounded-xl text-center font-medium hover:bg-emerald-100 transition-colors">
                    👥 Mes camarades
                </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
