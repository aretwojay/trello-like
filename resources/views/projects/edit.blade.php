@extends('layout.default')

@section('title', 'Modifier le projet')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Retour au projet
                </a>
            </div>
            <h1 class="mt-2 text-3xl font-bold text-gray-900">Modifier le projet</h1>
            <p class="mt-1 text-sm text-gray-600">
                Modifiez les informations de votre projet
            </p>
        </div>

        <!-- Messages de succès ou d'erreur -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="{{ route('projects.update', $project) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Project Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nom du projet
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $project->name) }}"
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-300 @enderror"
                                   placeholder="Ex: Nouveau site web, Application mobile..."
                                   required>
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">
                            Choisissez un nom descriptif pour votre projet
                        </p>
                    </div>

                    <!-- Project Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <div class="mt-1">
                            <textarea name="description" 
                                      id="description" 
                                      rows="4"
                                      class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-300 @enderror"
                                      placeholder="Décrivez brièvement les objectifs et le contexte de votre projet...">{{ old('description', $project->description) }}</textarea>
                        </div>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">
                            Optionnel - Aidez les membres de l'équipe à comprendre le projet
                        </p>
                    </div>

                    <!-- Information Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    À propos de la modification de projet
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc space-y-1 pl-5">
                                        <li>Les modifications seront visibles pour tous les membres du projet</li>
                                        <li>Le slug du projet ne sera pas modifié pour préserver les liens</li>
                                        <li>Seul le propriétaire peut modifier ces informations</li>
                                        <li>Les tâches et colonnes existantes ne seront pas affectées</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('projects.show', $project) }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Enregistrer les modifications
                            </button>
                        </div>
                        
                        <!-- Actions dangereuses -->
                        <div class="flex items-center space-x-2">
                            <button type="button" 
                                    onclick="openDeleteModal()"
                                    class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Supprimer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Informations du projet -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">ℹ️ Informations du projet</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <h4 class="font-medium text-gray-900">Statistiques</h4>
                    <ul class="mt-2 space-y-1 text-gray-600">
                        <li>• Créé le {{ $project->created_at->format('d/m/Y à H:i') }}</li>
                        <li>• Dernière modification le {{ $project->updated_at->format('d/m/Y à H:i') }}</li>
                        <li>• {{ $project->tasks->count() }} tâches au total</li>
                        <li>• {{ $project->columns->count() }} colonnes configurées</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Équipe</h4>
                    <ul class="mt-2 space-y-1 text-gray-600">
                        <li>• Propriétaire : {{ $project->owner->name }}</li>
                        <li>• {{ $project->members->count() }} membres</li>
                        <li>• Slug du projet : <code class="bg-gray-200 px-1 rounded">{{ $project->slug }}</code></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-2">Supprimer le projet</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Êtes-vous sûr de vouloir supprimer le projet <strong>"{{ $project->name }}"</strong> ? 
                    Cette action est irréversible et supprimera également toutes les tâches associées.
                </p>
                <div class="mt-4">
                    <input type="text" 
                           id="confirmProjectName" 
                           placeholder="Tapez le nom du projet pour confirmer"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>
            </div>
            <div class="items-center px-4 py-3">
                <form id="deleteForm" method="POST" action="{{ route('projects.destroy', $project) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            id="confirmDeleteBtn"
                            disabled
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed mr-2">
                        Supprimer définitivement
                    </button>
                </form>
                <button onclick="closeDeleteModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on the name field
    document.getElementById('name').focus();
    
    // Character counter for description
    const description = document.getElementById('description');
    const maxLength = 1000;
    
    // Create character counter element
    const counter = document.createElement('div');
    counter.className = 'text-xs text-gray-500 text-right mt-1';
    description.parentNode.appendChild(counter);
    
    function updateCounter() {
        const remaining = maxLength - description.value.length;
        counter.textContent = `${description.value.length}/${maxLength} caractères`;
        
        if (remaining < 50) {
            counter.className = 'text-xs text-red-500 text-right mt-1';
        } else if (remaining < 100) {
            counter.className = 'text-xs text-yellow-500 text-right mt-1';
        } else {
            counter.className = 'text-xs text-gray-500 text-right mt-1';
        }
    }
    
    description.addEventListener('input', updateCounter);
    updateCounter();

    // Modal de suppression
    const confirmInput = document.getElementById('confirmProjectName');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const projectName = "{{ $project->name }}";
    
    confirmInput.addEventListener('input', function() {
        if (this.value === projectName) {
            confirmBtn.disabled = false;
            confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            confirmBtn.disabled = true;
            confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    });
});

function openDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('confirmProjectName').focus();
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('confirmProjectName').value = '';
    document.getElementById('confirmDeleteBtn').disabled = true;
    document.getElementById('confirmDeleteBtn').classList.add('opacity-50', 'cursor-not-allowed');
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection
