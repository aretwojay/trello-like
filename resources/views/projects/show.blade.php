@extends('layout.default')

@section('title', $project->name)

@section('content')
<!-- Bloquer le scroll du body quand le modal est ouvert -->
@if(request('show_modal') === 'create_task' || request('show_modal') === 'edit_task')
    <style>
        body { overflow: hidden; }
    </style>
@endif

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
                               class="px-3 py-1 text-sm font-medium rounded-md {{ !request('view') || request('view') === 'kanban' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                                Kanban
                            </a>
                            <a href="{{ route('projects.show', $project) }}?view=list" 
                               class="px-3 py-1 text-sm font-medium rounded-md {{ request('view') === 'list' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                                Liste
                            </a>
                            <a href="{{ route('projects.show', $project) }}?view=calendar" 
                               class="px-3 py-1 text-sm font-medium rounded-md {{ request('view') === 'calendar' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                                Calendrier
                            </a>
                        </div>

                        <!-- Bouton nouvelle tâche -->
                        <a href="{{ route('projects.show', $project) }}?show_modal=create_task" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 transition ease-in-out duration-150">
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

    <!-- Contenu principal - Vue Kanban -->
    <div class="flex-1 overflow-hidden">
        <div class="h-full p-4">
            <div class="flex space-x-4 h-full overflow-x-auto">
                @forelse($project->columns as $column)
                    <div class="flex-shrink-0 w-80" data-column-id="{{ $column->id }}">
                        <!-- En-tête de colonne -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
                            <div class="flex items-center justify-between p-4 border-b border-gray-100">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $column->color ?? '#e2e8f0' }}"></div>
                                    <h3 class="font-medium text-gray-900">{{ $column->name }}</h3>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $column->tasks->count() }}
                                    </span>
                                </div>
                                <button class="text-gray-400 hover:text-gray-600 p-1 rounded">
                                    <a href="{{ route('projects.show', $project) }}?show_modal=create_task&column_id={{ $column->id }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </a>
                                </button>
                            </div>
                        </div>

                        <!-- Liste des tâches -->
                        <div class="space-y-3 min-h-24">
                            @forelse($column->tasks->sortBy('order') as $task)
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
                                    <!-- Tâche header -->
                                    <div class="flex items-start justify-between mb-2">
                                        <h4 class="text-sm font-medium text-gray-900 leading-5">
                                            <a href="{{ route('projects.show', $project) }}?show_modal=edit_task&task_id={{ $task->id }}" class="hover:text-indigo-600">
                                                {{ $task->title }}
                                            </a>
                                        </h4>
                                        @if($task->priority && $task->priority !== 'medium')
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $task->priority === 'low' ? 'bg-gray-100 text-gray-800' : '' }}">
                                                {{ $task->priority === 'high' ? 'Haute' : ($task->priority === 'low' ? 'Basse' : 'Normale') }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($task->description)
                                        <p class="text-xs text-gray-600 mb-3">{{ Str::limit($task->description, 80) }}</p>
                                    @endif

                                    <!-- Tâche meta -->
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <div class="flex items-center space-x-2">
                                            @if($task->due_date)
                                                <span class="flex items-center {{ $task->due_date->isPast() && !$task->is_completed ? 'text-red-600' : '' }}">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ $task->due_date->format('d/m') }}
                                                </span>
                                            @endif
                                            
                                            @if($task->category)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" 
                                                      style="background-color: {{ $task->category->color }}20; color: {{ $task->category->color }}">
                                                    {{ $task->category->name }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex items-center space-x-2">
                                            @if($task->assignedUsers && $task->assignedUsers->isNotEmpty())
                                                <img class="h-5 w-5 rounded-full" 
                                                     src="https://ui-avatars.com/api/?name={{ urlencode($task->assignedUsers->first()->name) }}&color=7C3AED&background=EDE9FE" 
                                                     alt="{{ $task->assignedUsers->first()->name }}"
                                                     title="{{ $task->assignedUsers->first()->name }}">
                                            @endif
                                            
                                            <!-- Boutons d'action -->
                                            <div class="flex items-center space-x-1">
                                                <a href="{{ route('projects.show', $project) }}?show_modal=edit_task&task_id={{ $task->id }}" class="text-gray-400 hover:text-indigo-600 p-1" title="Modifier">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                <form method="POST" action="{{ route('tasks.destroy', [$project, $task]) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-600 p-1" title="Supprimer">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-400">
                                    <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="text-sm">Aucune tâche</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="flex-1 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune colonne</h3>
                            <p class="mt-1 text-sm text-gray-500">Les colonnes par défaut seront créées automatiquement.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal unifié pour créer/éditer une tâche -->
    @include('tasks.modals.task-form')
    
    <!-- Notification toast -->
    @include('components.toast')
    
    <!-- Styles et scripts des modals -->
    @include('tasks.modals.styles')
    @include('tasks.modals.scripts')
</div>

<style>
/* Scrollbar styling */
::-webkit-scrollbar {
    height: 8px;
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
@endsection
