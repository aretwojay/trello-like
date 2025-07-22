<!-- Modal nouvelle tâche -->
@if(request('show_modal') === 'create_task')
    <style>
        body { overflow: hidden; }
    </style>
    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="backdrop-filter: blur(2px);">
        <!-- Zone cliquable pour fermer le modal -->
        <div class="absolute inset-0" onclick="window.location.href='{{ route('projects.show', $project) }}?view=calendar'"></div>
        
        <!-- Contenu du modal -->
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto relative z-10" onclick="event.stopPropagation();">
            <div class="p-6">
                <!-- Header avec bouton fermer -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Nouvelle tâche</h3>
                    <a href="{{ route('projects.show', $project) }}?view=calendar" class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>

                <form action="{{ route('tasks.store', $project) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <input type="hidden" name="redirect_view" value="calendar">

                    <!-- Titre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                        <input type="text" 
                               name="title" 
                               value="{{ old('title') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm {{ $errors->has('title') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '' }}"
                               placeholder="Entrez le titre de la tâche"
                               required
                               autofocus>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                  placeholder="Décrivez cette tâche (optionnel)">{{ old('description') }}</textarea>
                    </div>

                    <!-- Colonne -->
                    @if($project->columns->isNotEmpty())
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Colonne *</label>
                            <select name="column_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    required>
                                @foreach($project->columns as $column)
                                    <option value="{{ $column->id }}" {{ old('column_id') == $column->id ? 'selected' : '' }}>
                                        {{ $column->name }} ({{ $column->tasks->count() }} tâches)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Priorité et Date d'échéance -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Priorité -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                            <select name="priority" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>🔵 Basse</option>
                                <option value="medium" {{ old('priority') === 'medium' || !old('priority') ? 'selected' : '' }}>⚪ Normale</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>🔴 Haute</option>
                            </select>
                        </div>

                        <!-- Date d'échéance -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Échéance</label>
                            <input type="date" 
                                   name="due_date"
                                   value="{{ old('due_date', request('date')) }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t mt-6">
                        <a href="{{ route('projects.show', $project) }}?view=calendar" 
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Créer la tâche
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
