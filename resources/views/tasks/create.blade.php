@extends('layout.default')

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
                <a href="{{ route('projects.show', $project) }}" class="hover:text-indigo-600">{{ $project->title }}</a>
                <svg class="mx-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span>Nouvelle tâche</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Créer une nouvelle tâche</h1>
            <p class="text-gray-600">Ajoutez une nouvelle tâche au projet "{{ $project->title }}"</p>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-lg shadow">
            <form action="{{ route('tasks.store', $project) }}" method="POST" class="p-6 space-y-6">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                
                @if(isset($column))
                    <input type="hidden" name="column_id" value="{{ $column->id }}">
                @endif

                <!-- Titre -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Titre de la tâche <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title') }}"
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
                              placeholder="Décrivez la tâche en détail...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Colonne (si pas pré-sélectionnée) -->
                @if(!isset($column))
                <div>
                    <label for="column_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Colonne <span class="text-red-500">*</span>
                    </label>
                    <select name="column_id" 
                            id="column_id" 
                            required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Sélectionner une colonne</option>
                        @foreach($project->columns as $projectColumn)
                            <option value="{{ $projectColumn->id }}" {{ old('column_id') == $projectColumn->id ? 'selected' : '' }}>
                                {{ $projectColumn->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('column_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Priorité -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                        Priorité
                    </label>
                    <select name="priority" 
                            id="priority" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Normale</option>
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Faible</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Élevée</option>
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
                           value="{{ old('due_date') }}"
                           min="{{ now()->format('Y-m-d') }}"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catégorie -->
                @if($project->categories->isNotEmpty())
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Catégorie
                    </label>
                    <select name="category_id" 
                            id="category_id" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Aucune catégorie</option>
                        @foreach($project->categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                @if($project->collaborators->isNotEmpty())
                <div>
                    <label for="assigned_user_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Assigner à
                    </label>
                    <select name="assigned_user_id" 
                            id="assigned_user_id" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Non assignée</option>
                        <option value="{{ $project->user->id }}" {{ old('assigned_user_id') == $project->user->id ? 'selected' : '' }}>
                            {{ $project->user->name }} (Propriétaire)
                        </option>
                        @foreach($project->collaborators as $collaborator)
                            <option value="{{ $collaborator->id }}" {{ old('assigned_user_id') == $collaborator->id ? 'selected' : '' }}>
                                {{ $collaborator->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex space-x-3">
                        <a href="{{ route('projects.show', $project) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Annuler
                        </a>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button type="submit" 
                                name="action" 
                                value="create_another"
                                class="inline-flex items-center px-4 py-2 border border-indigo-300 shadow-sm text-sm font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Créer et ajouter une autre
                        </button>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Créer la tâche
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Conseils -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Conseils pour créer une bonne tâche</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Utilisez un titre clair et concis</li>
                            <li>Ajoutez une description détaillée si nécessaire</li>
                            <li>Définissez une date d'échéance réaliste</li>
                            <li>Assignez la tâche à la bonne personne</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
