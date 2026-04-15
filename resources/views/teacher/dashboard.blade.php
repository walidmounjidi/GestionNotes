<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ __('Tableau de Bord Professeur') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Bienvenue, {{ Auth::user()->nom }} {{ Auth::user()->prenom }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 text-white">
                    <p class="text-indigo-100 text-sm font-medium">Mes Classes</p>
                    <p class="text-3xl font-bold mt-1">{{ $classes->count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white">
                    <p class="text-emerald-100 text-sm font-medium">Total Étudiants</p>
                    <p class="text-3xl font-bold mt-1">{{ $classesWithStats->sum('students_count') }}</p>
                </div>
                <div class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl p-6 text-white">
                    <p class="text-violet-100 text-sm font-medium">Spécialisations</p>
                    <p class="text-3xl font-bold mt-1">{{ $specializations->count() }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-semibold text-slate-800">Mes Classes</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Classe</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Spécialisation</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Niveau</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Étudiants</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($classesWithStats as $item)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $item['classe']->libelle }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $item['classe']->specialization?->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $item['classe']->level?->name ?? $item['classe']->niveau }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $item['students_count'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('classes.show', $item['classe']->id) }}" class="text-indigo-600 hover:text-indigo-900">Voir</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">
                                    Aucune classe assignée. Contactez un administrateur.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($specializations->count() > 0)
            <div class="mt-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Mes Spécialisations</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($specializations as $spec)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                        {{ $spec->name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
