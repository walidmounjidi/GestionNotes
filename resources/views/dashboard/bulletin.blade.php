<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ __('Bulletin de Notes') }}
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 shadow-lg text-white">
                        <p class="text-indigo-100 text-sm font-medium">Moyenne Générale</p>
                        <p class="text-4xl font-bold mt-2">{{ $moyenne_generale ?? '--' }}/20</p>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 shadow-lg text-white">
                        <p class="text-emerald-100 text-sm font-medium">Classement</p>
                        <p class="text-4xl font-bold mt-2">
                            @if($classRanking && $classRanking['rank'])
                                {{ $classRanking['rank'] }}<span class="text-xl">/{{ $classRanking['total'] }}</span>
                            @else
                                --
                            @endif
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl p-6 shadow-lg text-white">
                        <p class="text-violet-100 text-sm font-medium">Classe</p>
                        <p class="text-4xl font-bold mt-2">{{ $etudiant->classe->libelle ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="font-semibold text-slate-800">Notes par Matière</h3>
                    </div>
                    <table class="w-full">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase">Matière</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-slate-600 uppercase">Coefficient</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-slate-600 uppercase">Nombre de Notes</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-slate-600 uppercase">Moyenne</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($bulletinData as $data)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 font-medium text-slate-800">{{ $data['matiere']->libelle }}</td>
                                    <td class="px-6 py-3 text-center text-slate-600">{{ $data['coefficient'] }}</td>
                                    <td class="px-6 py-3 text-center text-slate-600">{{ $data['total_notes'] }}</td>
                                    <td class="px-6 py-3 text-center">
                                        @if($data['average'] !== null)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                                {{ $data['average'] >= 16 ? 'bg-emerald-100 text-emerald-700' : 
                                                   ($data['average'] >= 10 ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700') }}">
                                                {{ number_format($data['average'], 2) }}/20
                                            </span>
                                        @else
                                            <span class="text-slate-400">--</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                        Aucune note disponible
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>