<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier l\'Étudiant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('etudiants.update', $etudiant->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <x-input-label for="nom" :value="__('Nom')" />
                                <x-text-input id="nom" class="block mt-1 w-full" type="text" name="nom" :value="old('nom', $etudiant->utilisateur->nom)" required autofocus />
                                <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                            </div>

                            <!-- Prénom -->
                            <div>
                                <x-input-label for="prenom" :value="__('Prénom')" />
                                <x-text-input id="prenom" class="block mt-1 w-full" type="text" name="prenom" :value="old('prenom', $etudiant->utilisateur->prenom)" required />
                                <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $etudiant->utilisateur->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Matricule -->
                            <div>
                                <x-input-label for="matricule" :value="__('Matricule')" />
                                <x-text-input id="matricule" class="block mt-1 w-full" type="text" name="matricule" :value="old('matricule', $etudiant->matricule)" required />
                                <x-input-error :messages="$errors->get('matricule')" class="mt-2" />
                            </div>

                            <!-- Date de naissance -->
                            <div>
                                <x-input-label for="date_naissance" :value="__('Date de naissance')" />
                                <x-text-input id="date_naissance" class="block mt-1 w-full" type="date" name="date_naissance" :value="old('date_naissance', $etudiant->date_naissance->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('date_naissance')" class="mt-2" />
                            </div>

                            <!-- Lieu de naissance -->
                            <div>
                                <x-input-label for="lieu_naissance" :value="__('Lieu de naissance')" />
                                <x-text-input id="lieu_naissance" class="block mt-1 w-full" type="text" name="lieu_naissance" :value="old('lieu_naissance', $etudiant->lieu_naissance)" required />
                                <x-input-error :messages="$errors->get('lieu_naissance')" class="mt-2" />
                            </div>

                            <!-- Sexe -->
                            <div>
                                <x-input-label for="sexe" :value="__('Sexe')" />
                                <select id="sexe" name="sexe" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="M" {{ $etudiant->sexe == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ $etudiant->sexe == 'F' ? 'selected' : '' }}>Féminin</option>
                                </select>
                                <x-input-error :messages="$errors->get('sexe')" class="mt-2" />
                            </div>

                            <!-- Classe -->
                            <div>
                                <x-input-label for="classe_id" :value="__('Classe')" />
                                <select id="classe_id" name="classe_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">Sélectionner...</option>
                                    @foreach(\App\Models\Classe::all() as $classe)
                                        <option value="{{ $classe->id }}" {{ $etudiant->classe_id == $classe->id ? 'selected' : '' }}>{{ $classe->libelle }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('classe_id')" class="mt-2" />
                            </div>

                            <!-- Nom Arabe -->
                            <div>
                                <x-input-label for="nom_arabe" :value="__('Nom en arabe')" />
                                <x-text-input id="nom_arabe" class="block mt-1 w-full" type="text" name="nom_arabe" :value="old('nom_arabe', $etudiant->nom_arabe)" />
                                <x-input-error :messages="$errors->get('nom_arabe')" class="mt-2" />
                            </div>

                            <!-- Prénom Arabe -->
                            <div>
                                <x-input-label for="prenom_arabe" :value="__('Prénom en arabe')" />
                                <x-text-input id="prenom_arabe" class="block mt-1 w-full" type="text" name="prenom_arabe" :value="old('prenom_arabe', $etudiant->prenom_arabe)" />
                                <x-input-error :messages="$errors->get('prenom_arabe')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <x-secondary-button onclick="window.location='{{ route('etudiants.index') }}'" class="mr-3">
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
