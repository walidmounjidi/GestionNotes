<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Détails du Professeur') }}
            </h2>
            <a href="{{ route('teacher.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl font-bold text-indigo-600">{{ substr($teacher->prenom, 0, 1) }}{{ substr($teacher->nom, 0, 1) }}</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800">{{ $teacher->nom }} {{ $teacher->prenom }}</h3>
                            <p class="text-sm text-slate-500">{{ $teacher->email }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-sm text-slate-500">Classes</span>
                            <span class="font-semibold text-slate-700">{{ $teacher->classes->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-sm text-slate-500">Matières</span>
                            <span class="font-semibold text-slate-700">{{ $teacher->subjects->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-slate-500">Matières assignées</span>
                            <span class="font-semibold text-slate-700">{{ $teacher->matiereClasses->count() }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-semibold text-slate-800 mb-4">Matières enseignées</h3>
                    @forelse($teacher->subjects as $subject)
                        <span class="inline-block px-3 py-1 text-sm bg-violet-100 text-violet-700 rounded-full mr-2 mb-2">
                            {{ $subject->libelle }}
                        </span>
                    @empty
                        <p class="text-sm text-slate-400">Aucune matière assignée</p>
                    @endforelse
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-semibold text-slate-800 mb-4">Actions</h3>
                    <a href="{{ route('teacher.edit', $teacher->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Modifier
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-semibold text-slate-800">Classes assignées</h3>
                    </div>
                    <div class="p-0">
                        @forelse($teacher->classes as $classe)
                        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 last:border-b-0 hover:bg-slate-50/50 transition-colors">
                            <div>
                                <p class="font-medium text-slate-800">{{ $classe->libelle }}</p>
                                <p class="text-sm text-slate-500">{{ $classe->level->libelle ?? 'N/A' }} - {{ $classe->specialization->libelle ?? 'N/A' }}</p>
                            </div>
                            <span class="text-sm text-slate-400">{{ $classe->etudiants->count() }} étudiants</span>
                        </div>
                        @empty
                        <div class="px-6 py-8 text-center text-sm text-slate-400">Aucune classe assignée</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-semibold text-slate-800">Matières assignées</h3>
                    </div>
                    <div class="p-0">
                        @forelse($teacher->matiereClasses as $mc)
                        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 last:border-b-0 hover:bg-slate-50/50 transition-colors">
                            <div>
                                <p class="font-medium text-slate-800">{{ $mc->matiere->libelle ?? 'N/A' }}</p>
                                <p class="text-sm text-slate-500">{{ $mc->classe->libelle ?? 'N/A' }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                Assignée
                            </span>
                        </div>
                        @empty
                        <div class="px-6 py-8 text-center text-sm text-slate-400">Aucune matière assignée</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
