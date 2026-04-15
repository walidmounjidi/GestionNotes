<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $specialization->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('specializations.edit', $specialization->id) }}" class="px-4 py-2 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors">
                    Modifier
                </a>
                <a href="{{ route('specializations.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-indigo-50 rounded-2xl p-6 border border-indigo-100">
                    <p class="text-sm text-indigo-600 font-medium">Étudiants</p>
                    <p class="text-3xl font-bold text-indigo-800 mt-1">{{ $studentsCount }}</p>
                </div>
                <div class="bg-emerald-50 rounded-2xl p-6 border border-emerald-100">
                    <p class="text-sm text-emerald-600 font-medium">Niveaux</p>
                    <p class="text-3xl font-bold text-emerald-800 mt-1">{{ $specialization->levels->count() }}</p>
                </div>
                <div class="bg-violet-50 rounded-2xl p-6 border border-violet-100">
                    <p class="text-sm text-violet-600 font-medium">Classes</p>
                    <p class="text-3xl font-bold text-violet-800 mt-1">{{ $specialization->classes->count() }}</p>
                </div>
            </div>

            @if($specialization->description)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
                <h3 class="font-semibold text-slate-800 mb-2">Description</h3>
                <p class="text-slate-600">{{ $specialization->description }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="font-semibold text-slate-800">Niveaux</h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($specialization->levels as $level)
                        <div class="px-6 py-4 flex justify-between items-center">
                            <span class="text-slate-700">{{ $level->name }}</span>
                            <span class="text-xs text-slate-500">Ordre: {{ $level->order }}</span>
                        </div>
                        @empty
                        <div class="px-6 py-8 text-center text-slate-500">Aucun niveau</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="font-semibold text-slate-800">Classes</h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($specialization->classes as $classe)
                        <div class="px-6 py-4 flex justify-between items-center">
                            <div>
                                <span class="text-slate-700 font-medium">{{ $classe->libelle }}</span>
                                <span class="text-xs text-slate-500 ms-2">({{ $classe->code }})</span>
                            </div>
                            <span class="text-sm text-indigo-600">{{ $classe->etudiants->count() }} étudiants</span>
                        </div>
                        @empty
                        <div class="px-6 py-8 text-center text-slate-500">Aucune classe</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
