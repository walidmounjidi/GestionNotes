<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Ajouter une Évaluation') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-semibold text-slate-800">Informations de l'évaluation</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('evaluations.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="matiere_id" :value="__('Matière')" />
                                <select id="matiere_id" name="matiere_id" class="block mt-1 w-full border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" required>
                                    <option value="">Sélectionner...</option>
                                    @foreach($matieres as $matiere)
                                        <option value="{{ $matiere->id }}">{{ $matiere->libelle }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('matiere_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="classe_id" :value="__('Classe')" />
                                <select id="classe_id" name="classe_id" class="block mt-1 w-full border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" required>
                                    <option value="">Sélectionner...</option>
                                    @foreach($classes as $classe)
                                        <option value="{{ $classe->id }}">{{ $classe->libelle }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('classe_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="type" :value="__('Type d\'évaluation')" />
                                <x-text-input id="type" class="block mt-1 w-full" type="text" name="type" :value="old('type')" placeholder="Ex: Devoir 1, Examen final..." required />
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="date_evaluation" :value="__('Date')" />
                                <x-text-input id="date_evaluation" class="block mt-1 w-full" type="date" name="date_evaluation" :value="old('date_evaluation')" required />
                                <x-input-error :messages="$errors->get('date_evaluation')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="note_max" :value="__('Note maximale')" />
                                <x-text-input id="note_max" class="block mt-1 w-full" type="number" name="note_max" :value="old('note_max', 20)" required min="0" step="0.01" />
                                <x-input-error :messages="$errors->get('note_max')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="coefficient" :value="__('Coefficient')" />
                                <x-text-input id="coefficient" class="block mt-1 w-full" type="number" name="coefficient" :value="old('coefficient', 1)" required min="0.1" step="0.1" />
                                <x-input-error :messages="$errors->get('coefficient')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="session" :value="__('Session')" />
                                <select id="session" name="session" class="block mt-1 w-full border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" required>
                                    <option value="principal">Principal</option>
                                    <option value="rattrapage">Rattrapage</option>
                                </select>
                                <x-input-error :messages="$errors->get('session')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-8">
                            <button type="button" onclick="window.location='{{ route('evaluations.index') }}'" class="px-5 py-2.5 text-slate-600 hover:bg-slate-100 font-medium rounded-lg transition-colors me-3">
                                Annuler
                            </button>
                            <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors shadow-sm">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
