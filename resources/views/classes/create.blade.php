<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Ajouter une Classe') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-semibold text-slate-800">Informations de la classe</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('classes.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="code" :value="__('Code')" />
                                <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" :value="old('code')" required />
                                <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="libelle" :value="__('Libellé')" />
                                <x-text-input id="libelle" class="block mt-1 w-full" type="text" name="libelle" :value="old('libelle')" required />
                                <x-input-error :messages="$errors->get('libelle')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="niveau" :value="__('Niveau')" />
                                <x-text-input id="niveau" class="block mt-1 w-full" type="text" name="niveau" :value="old('niveau')" required />
                                <x-input-error :messages="$errors->get('niveau')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="annee_scolaire" :value="__('Année scolaire')" />
                                <x-text-input id="annee_scolaire" class="block mt-1 w-full" type="number" name="annee_scolaire" :value="old('annee_scolaire', date('Y'))" required />
                                <x-input-error :messages="$errors->get('annee_scolaire')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-slate-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-8">
                            <button type="button" onclick="window.location='{{ route('classes.index') }}'" class="px-5 py-2.5 text-slate-600 hover:bg-slate-100 font-medium rounded-lg transition-colors me-3">
                                Annuler
                            </button>
                            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors shadow-sm">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
