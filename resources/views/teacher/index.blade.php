<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Gestion des Professeurs') }}
            </h2>
            <a href="{{ route('teacher.store') }}" onclick="event.preventDefault(); document.getElementById('create-teacher-form').submit();" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajouter
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success') && session('credentials'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 mb-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-emerald-800 mb-2">{{ session('success') }}</h3>
                        <p class="text-sm text-emerald-700 mb-4">Voici les identifiants de connexion pour le professeur:</p>
                        <div class="bg-white rounded-lg p-4 border border-emerald-200">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-emerald-600 uppercase tracking-wider">Email</p>
                                    <p class="font-mono font-semibold text-emerald-800">{{ session('credentials')['email'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-emerald-600 uppercase tracking-wider">Mot de passe temporaire</p>
                                    <p class="font-mono font-semibold text-emerald-800">{{ session('credentials')['password'] }}</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-emerald-600 mt-3">Important: Veuillez communiquer ces identifiants au professeur de manière sécurisée.</p>
                    </div>
                </div>
            </div>
            @endif

            @if(session('success') && !session('credentials'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6 flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-emerald-700 font-medium">{{ session('success') }}</span>
            </div>
            @endif

            <form id="create-teacher-form" method="GET" action="{{ route('teacher.create') }}" style="display: none;"></form>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Professeur</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Spécialisations</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Classes</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($teachers as $teacher)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <span class="text-indigo-600 font-semibold">{{ substr($teacher->prenom, 0, 1) }}{{ substr($teacher->nom, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-700">{{ $teacher->nom }} {{ $teacher->prenom }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $teacher->email }}</td>
                                <td class="px-6 py-4">
                                    @forelse($teacher->specializations as $spec)
                                        <span class="inline-block px-2 py-0.5 text-xs bg-violet-100 text-violet-700 rounded-full mb-1">
                                            {{ $spec->libelle }}
                                        </span>
                                    @empty
                                        <span class="text-slate-400 text-sm">Aucune</span>
                                    @endforelse
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                        {{ $teacher->classes->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('teacher.show', $teacher->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Voir">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('teacher.edit', $teacher->id) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-400">
                                    Aucun professeur trouvé. <a href="{{ route('teacher.create') }}" class="text-indigo-600 hover:text-indigo-800">Créer le premier professeur</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
