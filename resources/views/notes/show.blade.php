<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Détails de la Note') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informations</h3>
                        <div class="flex gap-2">
                            <x-secondary-button onclick="window.location='{{ route('notes.edit', $note->id) }}'">
                                {{ __('Modifier') }}
                            </x-secondary-button>
                            <x-secondary-button onclick="window.location='{{ route('notes.index') }}'">
                                {{ __('Retour') }}
                            </x-secondary-button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Étudiant</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                                {{ $note->etudiant->utilisateur->nom ?? '' }} {{ $note->etudiant->utilisateur->prenom ?? '' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Matricule</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $note->etudiant->matricule ?? '' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Matière</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $note->evaluation->matiere->libelle ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Évaluation</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $note->evaluation->type ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Note</p>
                            <p class="text-2xl font-bold {{ $note->note >= 10 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $note->note }}/20
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Observation</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $note->observation ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
