<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Ajouter une Matière') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-semibold text-slate-800">Informations de la matière</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('matieres.store') }}">
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
                                <x-input-label for="libelle_arabe" :value="__('Libellé en arabe')" />
                                <x-text-input id="libelle_arabe" class="block mt-1 w-full" type="text" name="libelle_arabe" :value="old('libelle_arabe')" />
                                <x-input-error :messages="$errors->get('libelle_arabe')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="coefficient" :value="__('Coefficient')" />
                                <x-text-input id="coefficient" class="block mt-1 w-full" type="number" name="coefficient" :value="old('coefficient', 1)" required min="1" />
                                <x-input-error :messages="$errors->get('coefficient')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="credits" :value="__('Crédits')" />
                                <x-text-input id="credits" class="block mt-1 w-full" type="number" name="credits" :value="old('credits', 3)" required min="1" />
                                <x-input-error :messages="$errors->get('credits')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-slate-200 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-8">
                            <button type="button" onclick="window.location='{{ route('matieres.index') }}'" class="px-5 py-2.5 text-slate-600 hover:bg-slate-100 font-medium rounded-lg transition-colors me-3">
                                Annuler
                            </button>
                            <button type="submit" class="px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-medium rounded-lg transition-colors shadow-sm">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
