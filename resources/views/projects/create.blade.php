@extends('layout.default')

@section('title', 'Créer un projet')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('projects.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Retour aux projets
                </a>
            </div>
            <h1 class="mt-2 text-3xl font-bold text-gray-900">Créer un nouveau projet</h1>
            <p class="mt-1 text-sm text-gray-600">
                Créez un espace de travail collaboratif pour organiser vos tâches
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
                    @csrf

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
                                   value="{{ old('name') }}"
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
                                      placeholder="Décrivez brièvement les objectifs et le contexte de votre projet...">{{ old('description') }}</textarea>
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
                                    À propos de la création de projet
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc space-y-1 pl-5">
                                        <li>Vous serez automatiquement le propriétaire du projet</li>
                                        <li>Des colonnes par défaut seront créées (À faire, En cours, Terminé)</li>
                                        <li>Vous pourrez inviter des membres après la création</li>
                                        <li>Toutes les fonctionnalités Kanban seront disponibles immédiatement</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('projects.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Créer le projet
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Template Ideas -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">💡 Idées de projets</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <h4 class="font-medium text-gray-900">Développement Web</h4>
                    <p class="text-sm text-gray-600 mt-1">Gérez les tâches de développement, bugs, et déploiements</p>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <h4 class="font-medium text-gray-900">Campagne Marketing</h4>
                    <p class="text-sm text-gray-600 mt-1">Planifiez le contenu, les publications et les analyses</p>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <h4 class="font-medium text-gray-900">Événement</h4>
                    <p class="text-sm text-gray-600 mt-1">Organisez la logistique, les invitations et le suivi</p>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <h4 class="font-medium text-gray-900">Équipe Produit</h4>
                    <p class="text-sm text-gray-600 mt-1">Suivez les fonctionnalités, tests et retours clients</p>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <h4 class="font-medium text-gray-900">Projet Personnel</h4>
                    <p class="text-sm text-gray-600 mt-1">Organisez vos objectifs et tâches personnelles</p>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <h4 class="font-medium text-gray-900">Lancement Produit</h4>
                    <p class="text-sm text-gray-600 mt-1">Coordonnez le développement, marketing et ventes</p>
                </div>
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
});
</script>
@endsection
