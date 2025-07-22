@extends('layout.default')

@section('title', $project->name . ' - Vue Liste')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-4">
                <!-- Breadcrumb et titre -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('projects.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Projets
                    </a>
                    <div class="flex flex-col">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h1>
                        @if($project->description)
                            <p class="text-sm text-gray-600 mt-1">{{ $project->description }}</p>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-3">
                    <!-- Statistiques rapides -->
                    <div class="hidden md:flex items-center space-x-4 text-sm text-gray-500">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            {{ $project->tasks->count() }} tâches
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            {{ $project->allUsers()->count() }} membres
                        </span>
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $project->completion_percentage }}%"></div>
                        </div>
                        <span>{{ $project->completion_percentage }}%</span>
                    </div>

                    <!-- Actions du projet -->
                    <div class="flex items-center space-x-2">
                        <!-- Vue selector -->
                        <div class="flex bg-gray-100 rounded-lg p-1">
                            <a href="{{ route('projects.show', $project) }}" 
                               class="px-3 py-1 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700">
                                Kanban
                            </a>
                            <a href="{{ route('projects.show', $project) }}?view=list" 
                               class="px-3 py-1 text-sm font-medium rounded-md bg-white text-gray-900 shadow-sm">
                                Liste
                            </a>
                            <a href="{{ route('projects.show', $project) }}?view=calendar" 
                               class="px-3 py-1 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700">
                                Calendrier
                            </a>
                        </div>

                        <!-- Bouton nouvelle tâche -->
                        <a href="{{ route('projects.show', $project) }}?show_modal=create_task&view=list" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Nouvelle tâche
                        </a>

                        <!-- Menu options -->
                        @if($project->isOwnedBy(auth()->user()))
                            <div class="relative group">
                                <button class="p-2 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>

                                <div class="hidden group-hover:block absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-50">
                                    <div class="py-1">
                                        <a href="{{ route('projects.edit', $project) }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Modifier le projet
                                        </a>
                                        <a href="#" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                            </svg>
                                            Gérer les membres
                                        </a>
                                    </div>
                                    <div class="py-1">
                                        <form method="POST" action="{{ route('projects.destroy', $project) }}" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')" 
                                              style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="group flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                <svg class="mr-3 h-4 w-4 text-red-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Supprimer le projet
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal - Vue Liste -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filtres et recherche -->
        <div class="mb-6">
            <form method="GET" action="{{ route('projects.show', $project) }}" class="space-y-4">
                <input type="hidden" name="view" value="list">
                
                <!-- Première ligne : Recherche -->
                <div class="flex gap-4 items-center">
                    <div class="flex-1 min-w-64">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Rechercher une tâche..."
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deuxième ligne : Filtres et Tri -->
                <div class="flex flex-wrap gap-4 items-center justify-between">
                    <!-- Filtres -->
                    <div class="flex gap-2">
                        <select name="column" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Toutes les colonnes</option>
                            @foreach($project->columns as $column)
                                <option value="{{ $column->id }}" {{ request('column') == $column->id ? 'selected' : '' }}>
                                    {{ $column->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="priority" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Toutes les priorités</option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Haute</option>
                            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Normale</option>
                            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Basse</option>
                        </select>

                        <select name="status" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Tous les statuts</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Terminées</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En cours</option>
                            <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>En retard</option>
                        </select>

                        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:ring-2 focus:ring-indigo-500 text-sm">
                            Filtrer
                        </button>

                        @if(request()->hasAny(['search', 'column', 'priority', 'status', 'sort_by', 'sort_direction']))
                            <a href="{{ route('projects.show', $project) }}?view=list" class="px-4 py-2 text-gray-500 hover:text-gray-700 text-sm">
                                Réinitialiser
                            </a>
                        @endif
                    </div>

                    <!-- Options de tri -->
                    <div class="flex items-center space-x-2">
                        <label class="text-sm text-gray-600">Trier par :</label>
                        <select name="sort_by" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="created_at" {{ request('sort_by') === 'created_at' || !request('sort_by') ? 'selected' : '' }}>Date de création</option>
                            <option value="updated_at" {{ request('sort_by') === 'updated_at' ? 'selected' : '' }}>Dernière modification</option>
                            <option value="title" {{ request('sort_by') === 'title' ? 'selected' : '' }}>Titre</option>
                            <option value="due_date" {{ request('sort_by') === 'due_date' ? 'selected' : '' }}>Date d'échéance</option>
                            <option value="priority" {{ request('sort_by') === 'priority' ? 'selected' : '' }}>Priorité</option>
                            <option value="is_completed" {{ request('sort_by') === 'is_completed' ? 'selected' : '' }}>Statut</option>
                        </select>

                        <select name="sort_direction" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="desc" {{ request('sort_direction') === 'desc' || !request('sort_direction') ? 'selected' : '' }}>
                                @if(request('sort_by') === 'title')
                                    Z → A
                                @elseif(in_array(request('sort_by'), ['due_date', 'created_at', 'updated_at']))
                                    Plus récent → Plus ancien
                                @elseif(request('sort_by') === 'priority')
                                    Haute → Basse
                                @elseif(request('sort_by') === 'is_completed')
                                    Terminé → En cours
                                @else
                                    Décroissant
                                @endif
                            </option>
                            <option value="asc" {{ request('sort_direction') === 'asc' ? 'selected' : '' }}>
                                @if(request('sort_by') === 'title')
                                    A → Z
                                @elseif(in_array(request('sort_by'), ['due_date', 'created_at', 'updated_at']))
                                    Plus ancien → Plus récent
                                @elseif(request('sort_by') === 'priority')
                                    Basse → Haute
                                @elseif(request('sort_by') === 'is_completed')
                                    En cours → Terminé
                                @else
                                    Croissant
                                @endif
                            </option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Liste des tâches -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <!-- En-tête avec indicateur de tri -->
            @if($tasks->isNotEmpty())
                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <span>{{ $tasks->total() }} tâche{{ $tasks->total() > 1 ? 's' : '' }} trouvée{{ $tasks->total() > 1 ? 's' : '' }}</span>
                            
                            @if(request('sort_by') && request('sort_by') !== 'created_at')
                                <span class="ml-4 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                    Triées par 
                                    @switch(request('sort_by'))
                                        @case('title')
                                            titre
                                            @break
                                        @case('due_date')
                                            date d'échéance
                                            @break
                                        @case('priority')
                                            priorité
                                            @break
                                        @case('is_completed')
                                            statut
                                            @break
                                        @case('updated_at')
                                            dernière modification
                                            @break
                                        @default
                                            {{ request('sort_by') }}
                                    @endswitch
                                    
                                    @if(request('sort_direction') === 'asc')
                                        (croissant)
                                    @else
                                        (décroissant)
                                    @endif
                                </span>
                            @endif
                        </div>

                        <!-- Tri rapide -->
                        <div class="flex items-center space-x-2 text-sm">
                            <span class="text-gray-500">Tri rapide :</span>
                            
                            <!-- Bouton tri par priorité -->
                            <a href="{{ route('projects.show', $project) }}?{{ http_build_query(array_merge(request()->all(), ['sort_by' => 'priority', 'sort_direction' => request('sort_by') === 'priority' && request('sort_direction') === 'desc' ? 'asc' : 'desc'])) }}"
                               class="flex items-center px-2 py-1 rounded text-xs {{ request('sort_by') === 'priority' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                                Priorité
                                @if(request('sort_by') === 'priority')
                                    @if(request('sort_direction') === 'desc')
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                        </svg>
                                    @endif
                                @endif
                            </a>

                            <!-- Bouton tri par date d'échéance -->
                            <a href="{{ route('projects.show', $project) }}?{{ http_build_query(array_merge(request()->all(), ['sort_by' => 'due_date', 'sort_direction' => request('sort_by') === 'due_date' && request('sort_direction') === 'desc' ? 'asc' : 'desc'])) }}"
                               class="flex items-center px-2 py-1 rounded text-xs {{ request('sort_by') === 'due_date' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                                Échéance
                                @if(request('sort_by') === 'due_date')
                                    @if(request('sort_direction') === 'desc')
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                        </svg>
                                    @endif
                                @endif
                            </a>

                            <!-- Bouton tri par titre -->
                            <a href="{{ route('projects.show', $project) }}?{{ http_build_query(array_merge(request()->all(), ['sort_by' => 'title', 'sort_direction' => request('sort_by') === 'title' && request('sort_direction') === 'desc' ? 'asc' : 'desc'])) }}"
                               class="flex items-center px-2 py-1 rounded text-xs {{ request('sort_by') === 'title' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                                Titre
                                @if(request('sort_by') === 'title')
                                    @if(request('sort_direction') === 'desc')
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                        </svg>
                                    @endif
                                @endif
                            </a>

                            <!-- Bouton tri par statut -->
                            <a href="{{ route('projects.show', $project) }}?{{ http_build_query(array_merge(request()->all(), ['sort_by' => 'is_completed', 'sort_direction' => request('sort_by') === 'is_completed' && request('sort_direction') === 'desc' ? 'asc' : 'desc'])) }}"
                               class="flex items-center px-2 py-1 rounded text-xs {{ request('sort_by') === 'is_completed' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                                Statut
                                @if(request('sort_by') === 'is_completed')
                                    @if(request('sort_direction') === 'desc')
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                        </svg>
                                    @endif
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @forelse($tasks as $task)
                <div class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <!-- Info principale de la tâche -->
                            <div class="flex items-center space-x-4 flex-1">
                                <!-- Checkbox pour marquer comme terminé -->
                                <form method="POST" action="{{ route('tasks.toggle-complete', [$project, $task]) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="flex-shrink-0">
                                        @if($task->is_completed)
                                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <div class="w-5 h-5 border-2 border-gray-300 rounded-full hover:border-gray-400"></div>
                                        @endif
                                    </button>
                                </form>

                                <!-- Titre et description -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('tasks.edit', [$project, $task]) }}" class="text-lg font-medium text-gray-900 hover:text-indigo-600 {{ $task->is_completed ? 'line-through text-gray-500' : '' }}">
                                            {{ $task->title }}
                                        </a>
                                        
                                        <!-- Badges de priorité -->
                                        @if($task->priority && $task->priority !== 'medium')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $task->priority === 'low' ? 'bg-gray-100 text-gray-800' : '' }}">
                                                {{ $task->priority === 'high' ? '🔴 Haute' : '🔵 Basse' }}
                                            </span>
                                        @endif

                                        <!-- Badge de colonne -->
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $task->column->name }}
                                        </span>
                                    </div>

                                    @if($task->description)
                                        <p class="mt-1 text-sm text-gray-600 {{ $task->is_completed ? 'line-through' : '' }}">
                                            {{ Str::limit($task->description, 150) }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Métadonnées -->
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <!-- Date d'échéance -->
                                @if($task->due_date)
                                    <span class="flex items-center {{ $task->due_date->isPast() && !$task->is_completed ? 'text-red-600' : '' }}">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $task->due_date->format('d/m/Y') }}
                                    </span>
                                @endif

                                <!-- Assigné à -->
                                @if($task->assignedUsers && $task->assignedUsers->isNotEmpty())
                                    <div class="flex items-center space-x-1">
                                        @foreach($task->assignedUsers->take(3) as $user)
                                            <img class="h-6 w-6 rounded-full" 
                                                 src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7C3AED&background=EDE9FE" 
                                                 alt="{{ $user->name }}"
                                                 title="{{ $user->name }}">
                                        @endforeach
                                        @if($task->assignedUsers->count() > 3)
                                            <span class="text-xs text-gray-400">+{{ $task->assignedUsers->count() - 3 }}</span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('tasks.edit', [$project, $task]) }}" class="text-gray-400 hover:text-indigo-600 p-1" title="Modifier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('tasks.destroy', [$project, $task]) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 p-1" title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune tâche</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if(request()->hasAny(['search', 'column', 'priority', 'status']))
                            Aucune tâche ne correspond à vos critères de recherche.
                        @else
                            Commencez par créer une nouvelle tâche.
                        @endif
                    </p>
                    @if(!request()->hasAny(['search', 'column', 'priority', 'status']))
                        <div class="mt-6">
                            <a href="{{ route('projects.show', $project) }}?show_modal=create_task&view=list" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Créer une tâche
                            </a>
                        </div>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($tasks->hasPages())
            <div class="mt-6">
                {{ $tasks->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Modal nouvelle tâche (repris de la vue show) -->
    @if(request('show_modal') === 'create_task')
        <style>
            body { overflow: hidden; }
        </style>
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="backdrop-filter: blur(2px);">
            <!-- Zone cliquable pour fermer le modal -->
            <div class="absolute inset-0" onclick="window.location.href='{{ route('projects.show', $project) }}?view=list'"></div>
            
            <!-- Contenu du modal -->
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto relative z-10" onclick="event.stopPropagation();">
                <div class="p-6">
                    <!-- Header avec bouton fermer -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Nouvelle tâche</h3>
                        <a href="{{ route('projects.show', $project) }}?view=list" class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    </div>

                    <form action="{{ route('tasks.store', $project) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                        <input type="hidden" name="redirect_view" value="list">
                        
                        @if(request('column_id'))
                            <input type="hidden" name="column_id" value="{{ request('column_id') }}">
                        @endif

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
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm {{ $errors->has('description') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '' }}"
                                      placeholder="Décrivez cette tâche (optionnel)">{{ old('description') }}</textarea>
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
                                        <option value="{{ $column->id }}" {{ (request('column_id') == $column->id || old('column_id') == $column->id) ? 'selected' : '' }}>
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

                        <!-- Priorité et Date d'échéance sur la même ligne -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Priorité -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                                <select name="priority" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm {{ $errors->has('priority') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '' }}">
                                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>🔵 Basse</option>
                                    <option value="medium" {{ old('priority') === 'medium' || !old('priority') ? 'selected' : '' }}>⚪ Normale</option>
                                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>🔴 Haute</option>
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
                                       value="{{ old('due_date') }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm {{ $errors->has('due_date') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '' }}">
                                @error('due_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t mt-6">
                            <a href="{{ route('projects.show', $project) }}?view=list" 
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
</div>
@endsection
