<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Saisir les Notes') }} - {{ $evaluation->matiere->libelle }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Evaluation Info -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Type</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $evaluation->type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Classe</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $evaluation->classe->libelle }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Date</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $evaluation->date_evaluation->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Note Max</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $evaluation->note_max }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grades Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('notes.store', $evaluation->id) }}">
                        @csrf
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Matricule</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Étudiant</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Note</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Observation</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($etudiants as $etudiant)
                                    @php
                                        $noteExistante = $notes->where('etudiant_id', $etudiant->id)->first();
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $etudiant->matricule }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $etudiant->utilisateur->nom }} {{ $etudiant->utilisateur->prenom }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="hidden" name="notes[{{ $loop->index }}][etudiant_id]" value="{{ $etudiant->id }}">
                                            <x-text-input 
                                                type="number" 
                                                name="notes[{{ $loop->index }}][note]" 
                                                value="{{ old('notes.'.$loop->index.'.note', $noteExistante->note ?? '') }}"
                                                class="block w-24" 
                                                min="0" 
                                                max="{{ $evaluation->note_max }}" 
                                                step="0.01"
                                            />
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <x-text-input 
                                                type="text" 
                                                name="notes[{{ $loop->index }}][observation]" 
                                                value="{{ old('notes.'.$loop->index.'.observation', $noteExistante->observation ?? '') }}"
                                                class="block w-full" 
                                                placeholder="Observation..."
                                            />
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end mt-6">
                            <x-secondary-button onclick="window.location='{{ route('evaluations.index') }}'" class="mr-3">
                                {{ __('Annuler') }}
                            </x-secondary-button>
                            <x-primary-button type="submit">
                                {{ __('Enregistrer les Notes') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
