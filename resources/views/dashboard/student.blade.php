<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ __('Tableau de Bord Étudiant') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Bienvenue, {{ Auth::user()->nom }} {{ Auth::user()->prenom }}</p>
            </div>
            <span class="text-sm text-slate-500">{{ now()->format('d/m/Y H:i') }}</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 shadow-lg shadow-indigo-500/25 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 text-sm font-medium">Moyenne Générale</p>
                            <p class="text-3xl font-bold mt-1">
                                @if($moyenne_generale !== null)
                                    {{ number_format($moyenne_generale, 2) }}/20
                                @else
                                    --
                                @endif
                            </p>
                            @if($progressIndicator)
                            <span class="inline-block mt-2 px-2 py-1 text-xs font-medium bg-white/20 rounded-full">
                                {{ $progressIndicator['label'] }}
                            </span>
                            @endif
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 shadow-lg shadow-emerald-500/25 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-emerald-100 text-sm font-medium">Total Matières</p>
                            <p class="text-3xl font-bold mt-1">{{ count($mySubjects) }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253m0-13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332-.477-4.5-1.253"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl p-6 shadow-lg shadow-violet-500/25 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-violet-100 text-sm font-medium">Total Notes</p>
                            <p class="text-3xl font-bold mt-1">{{ count($myNotes) }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            @if($upcomingEvaluations->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
                <h3 class="font-semibold text-slate-800 mb-4">Évaluations à venir</h3>
                <div class="space-y-3">
                    @foreach($upcomingEvaluations as $eval)
                    <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0">
                        <div>
                            <span class="font-medium text-slate-700">{{ $eval->matiere->libelle ?? 'N/A' }}</span>
                            @php
                                $typeLabels = [
                                    'test_1' => 'Test 1',
                                    'test_2' => 'Test 2',
                                    'test_3' => 'Test 3',
                                    'examen_final' => 'Examen Final',
                                ];
                            @endphp
                            <span class="text-sm text-slate-500 block">
                                {{ $typeLabels[$eval->type] ?? ucfirst(str_replace('_', ' ', $eval->type)) }}
                            </span>
                        </div>
                        <span class="text-sm text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                            {{ \Carbon\Carbon::parse($eval->date_evaluation)->format('d/m/Y') }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-semibold text-slate-800">Mes Notes par Matière</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Matière
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Test 1
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Test 2
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Test 3
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-indigo-500 uppercase tracking-wider">
                                    Examen Final
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Moyenne
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($gradeTable as $row)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700">
                                    {{ $row['matiere']->libelle }}
                                    <span class="block text-xs text-slate-400 font-normal">
                                        Coef. {{ $row['matiere']->coefficient }}
                                    </span>
                                </td>

                                @foreach(['test_1', 'test_2', 'test_3'] as $type)
                                <td class="px-4 py-3 text-center">
                                    @if($row['notes'][$type] !== null)
                                        <span class="px-2.5 py-1 rounded-lg text-sm font-semibold
                                            {{ $row['notes'][$type] >= 10
                                                ? 'bg-emerald-100 text-emerald-700'
                                                : 'bg-red-100 text-red-700' }}">
                                            {{ number_format($row['notes'][$type], 2) }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 text-sm">—</span>
                                    @endif
                                </td>
                                @endforeach

                                <td class="px-4 py-3 text-center">
                                    @if($row['notes']['examen_final'] !== null)
                                        <span class="px-2.5 py-1 rounded-lg text-sm font-semibold
                                            {{ $row['notes']['examen_final'] >= 10
                                                ? 'bg-indigo-100 text-indigo-700'
                                                : 'bg-red-100 text-red-700' }}">
                                            {{ number_format($row['notes']['examen_final'], 2) }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 text-sm">—</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-center">
                                    @if($row['moyenne'] !== null)
                                        <span class="text-sm font-bold
                                            {{ $row['moyenne'] >= 10
                                                ? 'text-emerald-600'
                                                : 'text-red-500' }}">
                                            {{ number_format($row['moyenne'], 2) }}/20
                                        </span>
                                    @else
                                        <span class="text-slate-300 text-sm">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-400">
                                    Aucune matière assignée à votre classe
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('student.bulletin') }}" class="flex items-center gap-3 p-4 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-slate-700">Voir le Bulletin</span>
                        <p class="text-xs text-slate-500">Consulter mes notes détaillées</p>
                    </div>
                </a>
                <a href="{{ route('student.schedule') }}" class="flex items-center gap-3 p-4 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-colors">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-slate-700">Calendrier</span>
                        <p class="text-xs text-slate-500">Voir les évaluations à venir</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>