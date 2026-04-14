<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier la Matière') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('matieres.update', $matiere->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Code -->
                            <div>
                                <x-input-label for="code" :value="__('Code')" />
                                <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" :value="old('code', $matiere->code)" required />
                                <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            </div>

                            <!-- Libellé -->
                            <div>
                                <x-input-label for="libelle" :value="__('Libellé')" />
                                <x-text-input id="libelle" class="block mt-1 w-full" type="text" name="libelle" :value="old('libelle', $matiere->libelle)" required />
                                <x-input-error :messages="$errors->get('libelle')" class="mt-2" />
                            </div>

                            <!-- Libellé Arabe -->
                            <div>
                                <x-input-label for="libelle_arabe" :value="__('Libellé en arabe')" />
                                <x-text-input id="libelle_arabe" class="block mt-1 w-full" type="text" name="libelle_arabe" :value="old('libelle_arabe', $matiere->libelle_arabe)" />
                                <x-input-error :messages="$errors->get('libelle_arabe')" class="mt-2" />
                            </div>

                            <!-- Coefficient -->
                            <div>
                                <x-input-label for="coefficient" :value="__('Coefficient')" />
                                <x-text-input id="coefficient" class="block mt-1 w-full" type="number" name="coefficient" :value="old('coefficient', $matiere->coefficient)" required min="1" />
                                <x-input-error :messages="$errors->get('coefficient')" class="mt-2" />
                            </div>

                            <!-- Crédits -->
                            <div>
                                <x-input-label for="credits" :value="__('Crédits')" />
                                <x-text-input id="credits" class="block mt-1 w-full" type="number" name="credits" :value="old('credits', $matiere->credits)" required min="1" />
                                <x-input-error :messages="$errors->get('credits')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $matiere->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <x-secondary-button onclick="window.location='{{ route('matieres.index') }}'" class="mr-3">
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
