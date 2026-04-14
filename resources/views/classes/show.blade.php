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
                            <x-secondary-button onclick="window.location='{{ route('classes.edit', $classe->id) }}'">
                                {{ __('Modifier') }}
                            </x-secondary-button>
                            <x-secondary-button onclick="window.location='{{ route('classes.index') }}'">
                                {{ __('Retour') }}
                            </x-secondary-button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @forelse($classe->matieres as $matiere)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $matiere->libelle }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Coefficient: {{ $matiere->coefficient }}</p>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Aucune matière assignée</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
