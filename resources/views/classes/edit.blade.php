<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier la Classe') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('classes.update', $classe->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Code -->
                            <div>
                                <x-input-label for="code" :value="__('Code')" />
                                <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" :value="old('code', $classe->code)" required />
                                <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            </div>

                            <!-- Libellé -->
                            <div>
                                <x-input-label for="libelle" :value="__('Libellé')" />
                                <x-text-input id="libelle" class="block mt-1 w-full" type="text" name="libelle" :value="old('libelle', $classe->libelle)" required />
                                <x-input-error :messages="$errors->get('libelle')" class="mt-2" />
                            </div>

                            <!-- Niveau -->
                            <div>
                                <x-input-label for="niveau" :value="__('Niveau')" />
                                <x-text-input id="niveau" class="block mt-1 w-full" type="text" name="niveau" :value="old('niveau', $classe->niveau)" required />
                                <x-input-error :messages="$errors->get('niveau')" class="mt-2" />
                            </div>

                            <!-- Année scolaire -->
                            <div>
                                <x-input-label for="annee_scolaire" :value="__('Année scolaire')" />
                                <x-text-input id="annee_scolaire" class="block mt-1 w-full" type="number" name="annee_scolaire" :value="old('annee_scolaire', $classe->annee_scolaire)" required />
                                <x-input-error :messages="$errors->get('annee_scolaire')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $classe->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <x-secondary-button onclick="window.location='{{ route('classes.index') }}'" class="mr-3">
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
