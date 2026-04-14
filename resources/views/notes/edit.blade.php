<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier la Note') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('notes.update', $note->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Étudiant -->
                            <div>
                                <x-input-label for="etudiant_id" :value="__('Étudiant')" />
                                <select id="etudiant_id" name="etudiant_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    @foreach($etudiants as $etudiant)
                                        <option value="{{ $etudiant->id }}" {{ $note->etudiant_id == $etudiant->id ? 'selected' : '' }}>
                                            {{ $etudiant->utilisateur->nom }} {{ $etudiant->utilisateur->prenom }} ({{ $etudiant->matricule }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('etudiant_id')" class="mt-2" />
                            </div>

                            <!-- Évaluation -->
                            <div>
                                <x-input-label for="evaluation_id" :value="__('Évaluation')" />
                                <select id="evaluation_id" name="evaluation_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    @foreach($evaluations as $evaluation)
                                        <option value="{{ $evaluation->id }}" {{ $note->evaluation_id == $evaluation->id ? 'selected' : '' }}>
                                            {{ $evaluation->matiere->libelle }} - {{ $evaluation->type }} ({{ $evaluation->classe->libelle }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('evaluation_id')" class="mt-2" />
                            </div>

                            <!-- Note -->
                            <div>
                                <x-input-label for="note" :value="__('Note')" />
                                <x-text-input id="note" class="block mt-1 w-full" type="number" name="note" :value="old('note', $note->note)" required min="0" max="20" step="0.01" />
                                <x-input-error :messages="$errors->get('note')" class="mt-2" />
                            </div>

                            <!-- Observation -->
                            <div>
                                <x-input-label for="observation" :value="__('Observation')" />
                                <x-text-input id="observation" class="block mt-1 w-full" type="text" name="observation" :value="old('observation', $note->observation)" />
                                <x-input-error :messages="$errors->get('observation')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <x-secondary-button onclick="window.location='{{ route('notes.index') }}'" class="mr-3">
                                {{ __('Annuler') }}
                            </x-secondary-button>
                            <x-primary-button type="submit">
                                {{ __('Mettre à jour') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
