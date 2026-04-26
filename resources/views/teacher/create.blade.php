<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Ajouter un Professeur') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-semibold text-slate-800">Informations du professeur</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('teacher.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="nom" :value="__('Nom')" />
                                <x-text-input id="nom" class="block mt-1 w-full" type="text" name="nom" :value="old('nom')" required autofocus />
                                <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="prenom" :value="__('Prénom')" />
                                <x-text-input id="prenom" class="block mt-1 w-full" type="text" name="prenom" :value="old('prenom')" required />
                                <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="subjects" :value="__('Matières enseignées')" />
                                <select id="subjects" name="subjects[]" multiple
                                        class="block mt-1 w-full border-slate-200 rounded-lg
                                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        size="6">
                                    @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}">
                                        {{ $matiere->libelle }} (Coef. {{ $matiere->coefficient }})
                                    </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-slate-500 mt-1">
                                    Maintenez Ctrl/Cmd pour sélectionner plusieurs matières
                                </p>
                                <x-input-error :messages="$errors->get('subjects')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="classes" :value="__('Classes')" />
                                <select id="classes" name="classes[]" multiple class="block mt-1 w-full border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}">
                                        {{ $classe->libelle }} ({{ $classe->level->libelle ?? 'N/A' }} - {{ $classe->specialization->libelle ?? 'N/A' }})
                                    </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-slate-500 mt-1">Maintenez Ctrl/Cmd pour sélectionner plusieurs classes</p>
                                <x-input-error :messages="$errors->get('classes')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                            <p class="text-sm text-amber-800">
                                <strong>Note:</strong> Un email et un mot de passe temporaire seront générés automatiquement pour ce professeur.
                                Les identifiants seront affichés après la création du compte.
                            </p>
                        </div>

                        <div class="flex justify-end mt-8">
                            <button type="button" onclick="window.location='{{ route('teacher.index') }}'" class="px-5 py-2.5 text-slate-600 hover:bg-slate-100 font-medium rounded-lg transition-colors me-3">
                                Annuler
                            </button>
                            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors shadow-sm">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
