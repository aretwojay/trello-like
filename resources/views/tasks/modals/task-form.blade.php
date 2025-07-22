<!-- Modal unifié pour créer/éditer une tâche -->
@if(request('show_modal') === 'create_task' || (request('show_modal') === 'edit_task' && isset($editTask)))
    @php
        $isEdit = request('show_modal') === 'edit_task' && isset($editTask);
        $task = $isEdit ? $editTask : null;
        $modalTitle = $isEdit ? 'Modifier la tâche' : 'Nouvelle tâche';
        $submitText = $isEdit ? 'Enregistrer' : 'Créer la tâche';
        $formAction = $isEdit ? route('tasks.update', [$project, $task]) : route('tasks.store', $project);
    @endphp

    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="backdrop-filter: blur(2px);" data-modal-open="{{ $isEdit ? 'edit' : 'create' }}">
        <!-- Zone cliquable pour fermer le modal -->
        <div class="absolute inset-0" onclick="window.location.href='{{ route('projects.show', $project) }}'"></div>
        
        <!-- Contenu du modal -->
        <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto relative z-10" onclick="event.stopPropagation();">
            <div class="p-6">
                <!-- Header avec bouton fermer -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $modalTitle }}</h3>
                    <a href="{{ route('projects.show', $project) }}" class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>

                <form action="{{ $formAction }}" method="POST" class="space-y-4 modal-form">
                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @else
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                        
                        @if(request('column_id'))
                            <input type="hidden" name="column_id" value="{{ request('column_id') }}">
                        @endif
                    @endif

                    <!-- Titre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                        <input type="text" 
                               name="title" 
                               value="{{ old('title', $task->title ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm {{ $errors->has('title') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '' }}"
                               placeholder="Entrez le titre de la tâche"
                               required
                               autofocus>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" 
                                  rows="{{ $isEdit ? 4 : 3 }}"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm {{ $errors->has('description') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '' }}"
                                  placeholder="Décrivez cette tâche (optionnel)">{{ old('description', $task->description ?? '') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Colonne -->
                    @if($project->columns->isNotEmpty())
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Colonne *</label>
                            <select name="column_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm {{ $errors->has('column_id') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '' }}"
                                    required>
                                @foreach($project->columns as $column)
                                    @php
                                        $selected = false;
                                        if ($isEdit) {
                                            $selected = old('column_id', $task->column_id) == $column->id;
                                        } else {
                                            $selected = (request('column_id') == $column->id || old('column_id') == $column->id);
                                        }
                                    @endphp
                                    <option value="{{ $column->id }}" {{ $selected ? 'selected' : '' }}>
                                        {{ $column->name }} ({{ $column->tasks->count() }} tâches)
                                    </option>
                                @endforeach
                            </select>
                            @error('column_id')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    @endif

                    <!-- Catégorie -->
                    @if($categories && $categories->isNotEmpty())
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                            <select name="category_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm {{ $errors->has('category_id') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '' }}">
                                <option value="">Aucune catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $task->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                        <span style="color: {{ $category->color }}">●</span> {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    @endif

                    <!-- Priorité et Date d'échéance sur la même ligne -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Priorité -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                            <select name="priority" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm {{ $errors->has('priority') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '' }}">
                                @if($isEdit)
                                    <option value="">Aucune priorité</option>
                                    <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>🔵 Basse</option>
                                    <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>⚪ Normale</option>
                                    <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>🔴 Haute</option>
                                @else
                                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>🔵 Basse</option>
                                    <option value="medium" {{ old('priority') === 'medium' || !old('priority') ? 'selected' : '' }}>⚪ Normale</option>
                                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>🔴 Haute</option>
                                @endif
                            </select>
                            @error('priority')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date d'échéance -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Échéance</label>
                            <input type="date" 
                                   name="due_date"
                                   value="{{ old('due_date', $task && $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                                   @if(!$isEdit) min="{{ date('Y-m-d') }}" @endif
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm {{ $errors->has('due_date') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '' }}">
                            @error('due_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Utilisateurs assignés -->
                    @if($users && $users->isNotEmpty())
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Utilisateurs assignés
                            </label>
                            <div class="space-y-2 max-h-32 overflow-y-auto border border-gray-200 rounded-md p-3">
                                @foreach($users as $user)
                                    @php
                                        $isChecked = false;
                                        if ($isEdit) {
                                            $isChecked = in_array($user->id, old('assigned_users', $task->assignedUsers->pluck('id')->toArray()));
                                        } else {
                                            $isChecked = in_array($user->id, old('assigned_users', []));
                                        }
                                    @endphp
                                    <label class="flex items-center space-x-3 p-1 hover:bg-gray-50 rounded cursor-pointer transition-colors">
                                        <input type="checkbox" 
                                               name="assigned_users[]" 
                                               value="{{ $user->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-6 h-6 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('assigned_users')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Statut de complétion (uniquement en édition) -->
                    @if($isEdit)
                        <div class="bg-gray-50 rounded-md p-3 border border-gray-200">
                            <label for="is_completed" class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       name="is_completed" 
                                       id="is_completed" 
                                       value="1"
                                       {{ old('is_completed', $task->is_completed) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <div class="text-sm">
                                    <span class="font-medium text-gray-900">Marquer comme terminée</span>
                                    <p class="text-gray-600">Cette tâche sera considérée comme accomplie</p>
                                </div>
                            </label>
                            @error('is_completed')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex {{ $isEdit ? 'justify-between items-center' : 'justify-end' }} pt-6 border-t mt-6">
                        @if($isEdit)
                            <!-- Bouton de suppression -->
                            @if(Auth::user()->can('delete', $task))
                                <button type="button"
                                        onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) { document.getElementById('delete-form-modal').submit(); }"
                                        class="inline-flex items-center px-3 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Supprimer
                                </button>
                            @endif
                        @endif

                        <div class="flex space-x-3">
                            <a href="{{ route('projects.show', $project) }}" 
                               class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                {{ $submitText }}
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Formulaire de suppression caché (uniquement en édition) -->
                @if($isEdit && Auth::user()->can('delete', $task))
                    <form id="delete-form-modal" action="{{ route('tasks.destroy', [$project, $task]) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>
    </div>
@endif
