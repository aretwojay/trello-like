@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-6">
            <div class="flex items-center text-sm text-gray-500 mb-2">
                <a href="{{ route('projects.index') }}" class="hover:text-indigo-600">Projets</a>
                <svg class="mx-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('projects.show', $task->project) }}" class="hover:text-indigo-600">{{ $task->project->title }}</a>
                <svg class="mx-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span>Modifier tâche</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Modifier la tâche</h1>
            <p class="text-gray-600">Modifiez les détails de la tâche "{{ $task->title }}"</p>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-lg shadow">
            <form action="{{ route('tasks.update', $task) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Titre -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Titre de la tâche <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', $task->title) }}"
                           required
                           maxlength="255"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Saisissez le titre de la tâche">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Description
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="4"
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="Décrivez la tâche en détail...">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statut -->
                <div>
                    <label for="is_completed" class="flex items-center">
                        <input type="checkbox" 
                               name="is_completed" 
                               id="is_completed" 
                               value="1"
                               {{ old('is_completed', $task->is_completed) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Tâche terminée</span>
                    </label>
                    @error('is_completed')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Colonne -->
                <div>
                    <label for="column_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Colonne <span class="text-red-500">*</span>
                    </label>
                    <select name="column_id" 
                            id="column_id" 
                            required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($task->project->columns as $column)
                            <option value="{{ $column->id }}" {{ old('column_id', $task->column_id) == $column->id ? 'selected' : '' }}>
                                {{ $column->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('column_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priorité -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                        Priorité
                    </label>
                    <select name="priority" 
                            id="priority" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Normale</option>
                        <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Faible</option>
                        <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>Élevée</option>
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date d'échéance -->
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Date d'échéance
                    </label>
                    <input type="date" 
                           name="due_date" 
                           id="due_date" 
                           value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catégorie -->
                @if($task->project->categories->isNotEmpty())
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Catégorie
                    </label>
                    <select name="category_id" 
                            id="category_id" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Aucune catégorie</option>
                        @foreach($task->project->categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $task->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Assignation -->
                @if($task->project->collaborators->isNotEmpty())
                <div>
                    <label for="assigned_user_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Assigner à
                    </label>
                    <select name="assigned_user_id" 
                            id="assigned_user_id" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Non assignée</option>
                        <option value="{{ $task->project->user->id }}" {{ old('assigned_user_id', $task->assignedUsers->first()?->id) == $task->project->user->id ? 'selected' : '' }}>
                            {{ $task->project->user->name }} (Propriétaire)
                        </option>
                        @foreach($task->project->collaborators as $collaborator)
                            <option value="{{ $collaborator->id }}" {{ old('assigned_user_id', $task->assignedUsers->first()?->id) == $collaborator->id ? 'selected' : '' }}>
                                {{ $collaborator->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Métadonnées -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Informations de la tâche</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Créée le:</span>
                            <span class="ml-1">{{ $task->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        <div>
                            <span class="font-medium">Modifiée le:</span>
                            <span class="ml-1">{{ $task->updated_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        @if($task->completed_at)
                        <div>
                            <span class="font-medium">Terminée le:</span>
                            <span class="ml-1">{{ $task->completed_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex space-x-3">
                        <a href="{{ route('projects.show', $task->project) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Retour au projet
                        </a>

                        <!-- Bouton supprimer -->
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ? Cette action est irréversible.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Supprimer
                            </button>
                        </form>
                    </div>
                    
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
