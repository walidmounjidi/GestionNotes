<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Gestion des Notes') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        @php
        $typeLabels = [
            'test_1' => 'Test 1',
            'test_2' => 'Test 2',
            'test_3' => 'Test 3',
            'examen_final' => 'Examen Final',
        ];
        @endphp
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl p-6 shadow-lg shadow-rose-500/25 text-white">
                    <p class="text-rose-100 text-sm font-medium">Total Notes</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 shadow-lg shadow-blue-500/25 text-white">
                    <p class="text-blue-100 text-sm font-medium">Moyenne Générale</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($stats['moyenne'], 2) }}/20</p>
                </div>
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 shadow-lg shadow-emerald-500/25 text-white">
                    <p class="text-emerald-100 text-sm font-medium">Notes Validées</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['validees'] }}</p>
                </div>
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 shadow-lg shadow-red-500/25 text-white">
                    <p class="text-red-100 text-sm font-medium">Notes Non Validées</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['non_valides'] }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <form method="GET" action="{{ route('notes.index') }}" class="flex flex-wrap gap-3">
                        <select name="classe_id" class="px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-colors">
                            <option value="">Toutes les classes</option>
                            @foreach(\App\Models\Classe::all() as $classe)
                                <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>{{ $classe->libelle }}</option>
                            @endforeach
                        </select>
                        <select name="matiere_id" class="px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-colors">
                            <option value="">Toutes les matières</option>
                            @foreach(\App\Models\Matiere::all() as $matiere)
                                <option value="{{ $matiere->id }}" {{ request('matiere_id') == $matiere->id ? 'selected' : '' }}>{{ $matiere->libelle }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-medium rounded-lg transition-colors">
                            Filtrer
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Étudiant</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Matière</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Évaluation</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Note</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($notes as $note)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    {{ $note->etudiant->utilisateur->nom }} {{ $note->etudiant->utilisateur->prenom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    {{ $note->evaluation->matiere->libelle }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    {{ $typeLabels[$note->evaluation->type ?? ''] ?? ($note->evaluation->description ?? 'N/A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $note->note >= 10 ? 'text-emerald-600' : 'text-red-500' }}">
                                    {{ $note->note }}/20
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $note->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                    {{ $notes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
