<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Détails de l\'Utilisateur') }}
            </h2>
            <a href="{{ route('utilisateurs.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">← Retour</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                        <span class="text-sm font-medium text-slate-500">Nom complet</span>
                        <span class="text-sm text-slate-900">{{ $utilisateur->nom }} {{ $utilisateur->prenom }}</span>
                    </div>

                    <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                        <span class="text-sm font-medium text-slate-500">Email</span>
                        <span class="text-sm text-slate-900">{{ $utilisateur->email }}</span>
                    </div>

                    <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                        <span class="text-sm font-medium text-slate-500">Téléphone</span>
                        <span class="text-sm text-slate-900">{{ $utilisateur->telephone ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                        <span class="text-sm font-medium text-slate-500">État</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $utilisateur->etat === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($utilisateur->etat) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-start pb-4 border-b border-slate-200">
                        <span class="text-sm font-medium text-slate-500">Rôles</span>
                        <div class="flex flex-wrap gap-1 justify-end">
                            @forelse($utilisateur->roles as $role)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $role->libelle }}
                                </span>
                            @empty
                                <span class="text-sm text-slate-400">Aucun rôle</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-4">
                        <span class="text-sm font-medium text-slate-500">Inscrit le</span>
                        <span class="text-sm text-slate-900">{{ $utilisateur->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <a href="{{ route('utilisateurs.edit', $utilisateur->id) }}" class="flex-1 px-4 py-2 bg-indigo-600 text-white text-center font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        Modifier
                    </a>
                    <form method="POST" action="{{ route('utilisateurs.destroy', $utilisateur->id) }}" class="flex-1" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
