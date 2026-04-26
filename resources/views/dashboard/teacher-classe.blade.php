<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard.teacher') }}" class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                        {{ __('Classe') }}: {{ $classe->libelle }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        {{ $classe->level->libelle ?? '' }} — {{ $classe->specialization->libelle ?? '' }}
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                <p class="text-sm text-emerald-700">{{ session('success') }}</p>
            </div>
            @endif

            @forelse($evaluationsByMatiere as $matiereId => $matiereData)
            @php
                $matiere = $matiereData['matiere'];
                $evals = $matiereData['evaluations'];
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-semibold text-xl text-slate-800">
                        {{ $matiere->libelle }}
                    </h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-violet-100 text-violet-700">
                        Coef. {{ $matiere->coefficient }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    @foreach(['test_1' => 'Test 1', 'test_2' => 'Test 2', 'test_3' => 'Test 3'] as $type => $label)
                    <div class="bg-slate-50 rounded-lg p-4">
                        <form method="POST" action="{{ route('teacher.classe.saveEvaluation', $classe->id) }}" class="flex flex-col gap-2">
                            @csrf
                            <input type="hidden" name="matiere_id" value="{{ $matiere->id }}">
                            <input type="hidden" name="type" value="{{ $type }}">
                            <label class="text-sm font-medium text-slate-700">{{ $label }}</label>
                            <input type="date" 
                                   name="date_evaluation" 
                                   value="{{ $evals[$type]?->date_evaluation?->format('Y-m-d') ?? '' }}"
                                   class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <button type="submit" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                                Enregistrer
                            </button>
                        </form>
                    </div>
                    @endforeach

                    <div class="bg-slate-50 rounded-lg p-4 flex flex-col justify-center">
                        <label class="text-sm font-medium text-slate-700 mb-2">Examen Final</label>
                        @if($evals['examen_final']?->date_evaluation)
                        <p class="text-emerald-600 font-medium">
                            {{ $evals['examen_final']->date_evaluation->format('d/m/Y') }}
                        </p>
                        @else
                        <p class="text-slate-400 text-sm">Date non définie</p>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Étudiant</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Test 1</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Test 2</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Test 3</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Examen Final</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Moyenne</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($etudiants as $etudiant)
                            @php
                                $studentNotes = $notesByEtudiant[$etudiant->id] ?? collect();
                                $note1 = $evals['test_1'] ? $studentNotes->where('evaluation_id', $evals['test_1']->id)->first()?->note : null;
                                $note2 = $evals['test_2'] ? $studentNotes->where('evaluation_id', $evals['test_2']->id)->first()?->note : null;
                                $note3 = $evals['test_3'] ? $studentNotes->where('evaluation_id', $evals['test_3']->id)->first()?->note : null;
                                $noteFinal = $evals['examen_final'] ? $studentNotes->where('evaluation_id', $evals['examen_final']->id)->first()?->note : null;
                                $notesForAvg = array_filter([$note1, $note2, $note3, $noteFinal]);
                                $moyenne = count($notesForAvg) > 0 ? array_sum($notesForAvg) / count($notesForAvg) : null;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700">
                                    {{ $etudiant->utilisateur->nom ?? '' }} {{ $etudiant->utilisateur->prenom ?? '' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($note1 !== null)
                                        <span class="px-2 py-1 rounded text-sm font-medium {{ $note1 >= 10 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                            {{ number_format($note1, 2) }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($note2 !== null)
                                        <span class="px-2 py-1 rounded text-sm font-medium {{ $note2 >= 10 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                            {{ number_format($note2, 2) }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($note3 !== null)
                                        <span class="px-2 py-1 rounded text-sm font-medium {{ $note3 >= 10 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                            {{ number_format($note3, 2) }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($noteFinal !== null)
                                        <span class="px-2 py-1 rounded text-sm font-medium {{ $noteFinal >= 10 ? 'bg-indigo-100 text-indigo-700' : 'bg-red-100 text-red-700' }}">
                                            {{ number_format($noteFinal, 2) }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center text-sm font-bold {{ $moyenne !== null && $moyenne >= 10 ? 'text-emerald-600' : ($moyenne !== null ? 'text-red-500' : 'text-slate-400') }}">
                                    {{ $moyenne !== null ? number_format($moyenne, 2) : '—' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-sm text-slate-400">Aucun étudiant dans cette classe</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @foreach(['test_1', 'test_2', 'test_3'] as $type)
                @if($evals[$type])
                <div class="mt-6 pt-6 border-t border-slate-200">
                    <details class="group">
                        <summary class="cursor-pointer flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            <svg class="w-4 h-4 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            Saisir les notes — {{ match($type) { 'test_1' => 'Test 1', 'test_2' => 'Test 2', 'test_3' => 'Test 3' } }}
                        </summary>
                        <form method="POST" action="{{ route('teacher.classe.saveNotes', $classe->id) }}" class="mt-4">
                            @csrf
                            <input type="hidden" name="evaluation_id" value="{{ $evals[$type]->id }}">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-slate-500 uppercase">Étudiant</th>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-slate-500 uppercase">Note</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($etudiants as $etudiant)
                                        @php
                                            $existingNote = $evals[$type] ? ($notesByEtudiant[$etudiant->id] ?? collect())->where('evaluation_id', $evals[$type]->id)->first()?->note : null;
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-slate-700">
                                                {{ $etudiant->utilisateur->nom ?? '' }} {{ $etudiant->utilisateur->prenom ?? '' }}
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="hidden" name="notes[{{ $loop->index }}][etudiant_id]" value="{{ $etudiant->id }}">
                                                <input type="number" 
                                                       name="notes[{{ $loop->index }}][note]" 
                                                       value="{{ $existingNote ?? '' }}"
                                                       class="w-24 border border-slate-200 rounded-lg px-3 py-1.5 text-sm"
                                                       min="0" max="20" step="0.01">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                                Enregistrer les notes
                            </button>
                        </form>
                    </details>
                </div>
                @else
                <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-sm text-amber-700">
                        Définissez d'abord la date du {{ match($type) { 'test_1' => 'Test 1', 'test_2' => 'Test 2', 'test_3' => 'Test 3' } }} pour saisir les notes.
                    </p>
                </div>
                @endif
                @endforeach
            </div>
            @empty
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 text-center">
                <p class="text-slate-400">Aucune matière assignée dans cette classe</p>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>