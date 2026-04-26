<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Ajouter un Professeur') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
                        </div>

                        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div x-data="{
                                selectedSubjects: [],
                                init() {
                                    this.selectedSubjects = [];
                                },
                                get count() {
                                    return this.selectedSubjects.length;
                                },
                                selectAll() {
                                    this.selectedSubjects = @js($matieres->pluck('id')->toArray());
                                },
                                clearAll() {
                                    this.selectedSubjects = [];
                                }
                            }">
                                <div class="flex items-center justify-between mb-2">
                                    <x-input-label for="subjects" :value="__('Matières enseignées')" />
                                    <span class="text-xs text-slate-500" x-show="count > 0" x-text="count + ' sélectionnée' + (count > 1 ? 's' : '')"></span>
                                </div>
                                <div class="flex gap-3 mb-3">
                                    <button type="button" @click="selectAll()" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Tout sélectionner</button>
                                    <span class="text-slate-300">|</span>
                                    <button type="button" @click="clearAll()" class="text-xs text-slate-500 hover:text-slate-700 font-medium">Tout effacer</button>
                                </div>
                                <div class="border border-slate-200 rounded-lg divide-y divide-slate-100 max-h-64 overflow-y-auto">
                                    @forelse($matieres as $matiere)
                                    <label class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 cursor-pointer transition-colors">
                                        <input type="checkbox" 
                                               name="subjects[]" 
                                               value="{{ $matiere->id }}"
                                               x-model="selectedSubjects"
                                               class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                                        <span class="text-sm text-slate-700">{{ $matiere->libelle }}</span>
                                        <span class="text-xs text-slate-400 ml-auto">Coef. {{ $matiere->coefficient }}</span>
                                    </label>
                                    @empty
                                    <div class="px-4 py-4 text-sm text-slate-400 text-center">Aucune matière disponible</div>
                                    @endforelse
                                </div>
                                <x-input-error :messages="$errors->get('subjects')" class="mt-2" />
                            </div>

                            <div x-data="{
                                selectedClasses: [],
                                init() {
                                    this.selectedClasses = [];
                                },
                                get count() {
                                    return this.selectedClasses.length;
                                },
                                selectAll() {
                                    this.selectedClasses = @js($classes->pluck('id')->toArray());
                                },
                                clearAll() {
                                    this.selectedClasses = [];
                                }
                            }">
                                <div class="flex items-center justify-between mb-2">
                                    <x-input-label for="classes" :value="__('Classes assignées')" />
                                    <span class="text-xs text-slate-500" x-show="count > 0" x-text="count + ' sélectionnée' + (count > 1 ? 's' : '')"></span>
                                </div>
                                <div class="flex gap-3 mb-3">
                                    <button type="button" @click="selectAll()" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Tout sélectionner</button>
                                    <span class="text-slate-300">|</span>
                                    <button type="button" @click="clearAll()" class="text-xs text-slate-500 hover:text-slate-700 font-medium">Tout effacer</button>
                                </div>
                                <div class="border border-slate-200 rounded-lg divide-y divide-slate-100 max-h-64 overflow-y-auto">
                                    @forelse($classes as $classe)
                                    <label class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 cursor-pointer transition-colors">
                                        <input type="checkbox" 
                                               name="classes[]" 
                                               value="{{ $classe->id }}"
                                               x-model="selectedClasses"
                                               class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                                        <span class="text-sm text-slate-700">{{ $classe->libelle }}</span>
                                        <span class="text-xs text-slate-400 ml-auto">{{ $classe->level->libelle ?? '' }} - {{ $classe->specialization->libelle ?? '' }}</span>
                                    </label>
                                    @empty
                                    <div class="px-4 py-4 text-sm text-slate-400 text-center">Aucune classe disponible</div>
                                    @endforelse
                                </div>
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