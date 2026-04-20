<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ __('Tableau de Bord Gestionnaire') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Bienvenue, {{ Auth::user()->nom }} {{ Auth::user()->prenom }}</p>
            </div>
            <span class="text-sm text-slate-500">{{ now()->format('d/m/Y H:i') }}</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 shadow-lg shadow-indigo-500/25 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 text-sm font-medium">Professeurs</p>
                            <p class="text-3xl font-bold mt-1">{{ $stats['total_teachers'] }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14s7 7 0 00-7-7z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-semibold text-slate-800">Professeurs</h3>
                    <span class="text-sm text-slate-500">{{ $teachers->count() }} professeurs</span>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Professeur</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Spécialisation</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Étudiants</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Classes</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($teachers as $teacher)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-4 py-3">
                                        <div class="text-sm">
                                            <div class="font-medium text-slate-700">{{ $teacher['name'] }}</div>
                                            <div class="text-slate-500 text-xs">{{ $teacher['email'] }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-600">
                                        @forelse($teacher['specializations'] as $spec)
                                            <span class="inline-block px-2 py-0.5 text-xs bg-violet-100 text-violet-700 rounded-full mb-1">
                                                {{ $spec }}
                                            </span>
                                        @empty
                                            <span class="text-slate-400 text-xs">Aucune</span>
                                        @endforelse
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-700 rounded-full font-semibold">
                                            {{ $teacher['students_count'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center font-medium text-slate-700">{{ $teacher['classes_count'] }}</td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <button onclick="toggleAssignModal({{ $teacher['id'] }})" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            Assigner
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-400">Aucun professeur trouvé</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-semibold text-slate-800 mb-4">Actions Rapides</h3>
                    <div class="space-y-3">
                        <a href="{{ route('classes.index') }}" class="flex items-center gap-3 p-3 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="text-sm font-medium text-slate-700">Gérer les Classes</span>
                        </a>
                        <a href="{{ route('utilisateurs.index') }}" class="flex items-center gap-3 p-3 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 4M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span class="text-sm font-medium text-slate-700">Gérer les Professeurs</span>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-semibold text-slate-800 mb-4">Statistiques</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Total Professeurs</span>
                            <span class="font-semibold text-slate-800">{{ $stats['total_teachers'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subject Assignment Modal -->
        <div id="assignModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-800">Assigner une Matière</h3>
                </div>
                <form id="assignForm" action="{{ route('manager.assignSubject') }}" method="POST">
                    @csrf
                    <input type="hidden" id="teacherId" name="teacher_id">
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Matière</label>
                            <select name="matiere_id" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Sélectionner une matière</option>
                                @foreach($matieres as $matiere)
                                <option value="{{ $matiere->id }}">{{ $matiere->libelle }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Classe</label>
                            <select name="classe_id" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Sélectionner une classe</option>
                                @foreach($classes as $classe)
                                <option value="{{ $classe->id }}">{{ $classe->libelle }} ({{ $classe->level->libelle ?? '' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                        <button type="button" onclick="toggleAssignModal()" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                            Assigner
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function toggleAssignModal(teacherId = null) {
                const modal = document.getElementById('assignModal');
                const hiddenInput = document.getElementById('teacherId');
                
                if (teacherId) {
                    hiddenInput.value = teacherId;
                    modal.classList.remove('hidden');
                } else {
                    modal.classList.add('hidden');
                    document.getElementById('assignForm').reset();
                }
            }
        </script>
    </div>
</x-app-layout>
