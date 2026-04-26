<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Détails de la Classe') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Class Info -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informations</h3>
                        <div class="flex gap-2">
                            @if(Auth::user()->hasRole(['admin', 'manager']))
                            <x-secondary-button onclick="window.location='{{ route('classes.edit', $classe->id) }}'">
                                {{ __('Modifier') }}
                            </x-secondary-button>
                            @endif
                            <x-secondary-button onclick="window.location='{{ route('classes.index') }}'">
                                {{ __('Retour') }}
                            </x-secondary-button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Code</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $classe->code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Libellé</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $classe->libelle }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Niveau</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $classe->niveau }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Année Scolaire</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $classe->annee_scolaire }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Professeurs</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                                {{ $classe->teachers->count() }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Matières</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                                {{ $classe->matieres->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Étudiants ({{ $classe->etudiants->count() }})</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Matricule</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nom</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Prénom</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Sexe</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($classe->etudiants as $etudiant)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $etudiant->matricule }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $etudiant->utilisateur->nom }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $etudiant->utilisateur->prenom }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $etudiant->sexe == 'M' ? 'Masculin' : 'Féminin' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Aucun étudiant</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Matières -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Matières ({{ $classe->matieres->count() }})</h3>
                    
                    <!-- Subsection A: Assigned Matieres -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        @forelse($classe->matieres as $matiere)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 relative">
                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $matiere->libelle }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Coefficient: {{ $matiere->coefficient }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $matiere->credits }} crédits</p>
                            @if(Auth::user()->isAdmin())
                            <form method="POST" action="{{ route('classes.removeMatiere', [$classe->id, $matiere->id]) }}" class="absolute top-2 right-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Retirer">×</button>
                            </form>
                            @endif
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Aucune matière assignée</p>
                        @endforelse
                    </div>
                    
                    <!-- Subsection B: Add Matieres Form (Admin Only) -->
                    @if(Auth::user()->isAdmin())
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Assigner des matières</h4>
                        @if($availableMatieres->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">Toutes les matières sont déjà assignées.</p>
                        @else
                        <form method="POST" action="{{ route('classes.assignMatiere', $classe->id) }}">
                            @csrf
                            <div class="flex flex-col sm:flex-row gap-4 items-start">
                                <select name="matiere_ids[]" multiple class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm min-h-[120px] min-w-[280px]" size="{{ min(6, $availableMatieres->count()) }}">
                                    @foreach($availableMatieres as $matiere)
                                    <option value="{{ $matiere->id }}">{{ $matiere->code }} — {{ $matiere->libelle }} (Coef. {{ $matiere->coefficient }})</option>
                                    @endforeach
                                </select>
                                <x-primary-button type="submit">{{ __('Assigner les matières sélectionnées') }}</x-primary-button>
                            </div>
                        </form>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
