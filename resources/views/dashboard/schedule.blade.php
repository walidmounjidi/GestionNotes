<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ __('Calendrier des Évaluations') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">{{ $etudiant->utilisateur->nom ?? '' }} {{ $etudiant->utilisateur->prenom ?? '' }}</p>
            </div>
            <span class="text-sm text-slate-500">{{ now()->format('d/m/Y') }}</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(!$etudiant)
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
                    <p>{{ $error ?? 'Profil étudiant non trouvé.' }}</p>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="font-semibold text-slate-800">Évaluations à venir</h3>
                    </div>
                    <div class="p-6">
                        @if($scheduleData->count() > 0)
                            <div class="space-y-6">
                                @foreach($scheduleData as $date => $evaluations)
                                    <div class="border-l-4 border-indigo-500 pl-6">
                                        <div class="text-lg font-semibold text-slate-800 mb-4">
                                            {{ \Carbon\Carbon::parse($date)->format('l d/m/Y') }}
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @foreach($evaluations as $evaluation)
                                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                                    <div class="font-medium text-slate-800">{{ $evaluation->matiere->libelle ?? 'N/A' }}</div>
                                                    <div class="text-sm text-slate-500 mt-1">
                                                        <span class="px-2 py-1 rounded-full text-xs {{ $evaluation->type === 'examen' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                                            {{ ucfirst($evaluation->type) }}
                                                        </span>
                                                    </div>
                                                    <div class="text-sm text-slate-500 mt-2">
                                                        Coefficient: {{ $evaluation->coefficient ?? 1 }}
                                                    </div>
                                                    @if($evaluation->description)
                                                        <div class="text-xs text-slate-400 mt-2">
                                                            {{ $evaluation->description }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 text-slate-400">
                                <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p>Aucune évaluation prévue</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('dashboard.student') }}" class="inline-flex items-center px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg transition-colors">
                        Retour au tableau de bord
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>