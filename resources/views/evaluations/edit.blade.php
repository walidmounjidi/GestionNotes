<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier l\'Évaluation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('evaluations.update', $evaluation->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Matière -->
                            <div>
                                <x-input-label for="matiere_id" :value="__('Matière')" />
                                <select id="matiere_id" name="matiere_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    @foreach($matieres as $matiere)
                                        <option value="{{ $matiere->id }}" {{ $evaluation->matiere_id == $matiere->id ? 'selected' : '' }}>{{ $matiere->libelle }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('matiere_id')" class="mt-2" />
                            </div>

                            <!-- Classe -->
                            <div>
                                <x-input-label for="classe_id" :value="__('Classe')" />
                                <select id="classe_id" name="classe_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    @foreach($classes as $classe)
                                        <option value="{{ $classe->id }}" {{ $evaluation->classe_id == $classe->id ? 'selected' : '' }}>{{ $classe->libelle }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('classe_id')" class="mt-2" />
                            </div>

                            <!-- Type -->
                            <div>
                                <x-input-label for="type" :value="__('Type d\'évaluation')" />
                                <x-text-input id="type" class="block mt-1 w-full" type="text" name="type" :value="old('type', $evaluation->type)" required />
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <!-- Date -->
                            <div>
                                <x-input-label for="date_evaluation" :value="__('Date')" />
                                <x-text-input id="date_evaluation" class="block mt-1 w-full" type="date" name="date_evaluation" :value="old('date_evaluation', $evaluation->date_evaluation->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('date_evaluation')" class="mt-2" />
                            </div>

                            <!-- Note Max -->
                            <div>
                                <x-input-label for="note_max" :value="__('Note maximale')" />
                                <x-text-input id="note_max" class="block mt-1 w-full" type="number" name="note_max" :value="old('note_max', $evaluation->note_max)" required min="0" step="0.01" />
                                <x-input-error :messages="$errors->get('note_max')" class="mt-2" />
                            </div>

                            <!-- Coefficient -->
                            <div>
                                <x-input-label for="coefficient" :value="__('Coefficient')" />
                                <x-text-input id="coefficient" class="block mt-1 w-full" type="number" name="coefficient" :value="old('coefficient', $evaluation->coefficient)" required min="0.1" step="0.1" />
                                <x-input-error :messages="$errors->get('coefficient')" class="mt-2" />
                            </div>

                            <!-- Session -->
                            <div>
                                <x-input-label for="session" :value="__('Session')" />
                                <select id="session" name="session" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="principal" {{ $evaluation->session == 'principal' ? 'selected' : '' }}>Principal</option>
                                    <option value="rattrapage" {{ $evaluation->session == 'rattrapage' ? 'selected' : '' }}>Rattrapage</option>
                                </select>
                                <x-input-error :messages="$errors->get('session')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $evaluation->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <x-secondary-button onclick="window.location='{{ route('evaluations.index') }}'" class="mr-3">
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
