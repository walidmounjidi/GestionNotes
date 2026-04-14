<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Ajouter un Étudiant') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-semibold text-slate-800">Informations de l'étudiant</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('etudiants.store') }}">
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

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('Mot de passe')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="matricule" :value="__('Matricule')" />
                                <x-text-input id="matricule" class="block mt-1 w-full" type="text" name="matricule" :value="old('matricule')" required />
                                <x-input-error :messages="$errors->get('matricule')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="date_naissance" :value="__('Date de naissance')" />
                                <x-text-input id="date_naissance" class="block mt-1 w-full" type="date" name="date_naissance" :value="old('date_naissance')" required />
                                <x-input-error :messages="$errors->get('date_naissance')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="lieu_naissance" :value="__('Lieu de naissance')" />
                                <x-text-input id="lieu_naissance" class="block mt-1 w-full" type="text" name="lieu_naissance" :value="old('lieu_naissance')" required />
                                <x-input-error :messages="$errors->get('lieu_naissance')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="sexe" :value="__('Sexe')" />
                                <select id="sexe" name="sexe" class="block mt-1 w-full border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                                </select>
                                <x-input-error :messages="$errors->get('sexe')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="nom_arabe" :value="__('Nom en arabe')" />
                                <x-text-input id="nom_arabe" class="block mt-1 w-full" type="text" name="nom_arabe" :value="old('nom_arabe')" />
                                <x-input-error :messages="$errors->get('nom_arabe')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="prenom_arabe" :value="__('Prénom en arabe')" />
                                <x-text-input id="prenom_arabe" class="block mt-1 w-full" type="text" name="prenom_arabe" :value="old('prenom_arabe')" />
                                <x-input-error :messages="$errors->get('prenom_arabe')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-8">
                            <button type="button" onclick="window.location='{{ route('etudiants.index') }}'" class="px-5 py-2.5 text-slate-600 hover:bg-slate-100 font-medium rounded-lg transition-colors me-3">
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
