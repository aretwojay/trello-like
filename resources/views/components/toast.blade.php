<!-- Notifications toast -->
@if(session('success') || session('error') || session('warning') || session('info'))
    <div id="toast-notification" 
         class="fixed top-4 right-4 z-50 max-w-sm bg-white rounded-lg shadow-lg border border-gray-200 transform transition-all duration-300 translate-x-full opacity-0"
         style="min-width: 300px;">
        <div class="p-4">
            @if(session('success'))
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">Succès</p>
                        <p class="text-sm text-gray-500">{{ session('success') }}</p>
                    </div>
                </div>
            @elseif(session('error'))
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">Erreur</p>
                        <p class="text-sm text-gray-500">{{ session('error') }}</p>
                    </div>
                </div>
            @elseif(session('warning'))
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">Attention</p>
                        <p class="text-sm text-gray-500">{{ session('warning') }}</p>
                    </div>
                </div>
            @elseif(session('info'))
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">Information</p>
                        <p class="text-sm text-gray-500">{{ session('info') }}</p>
                    </div>
                </div>
            @endif
            
            <!-- Bouton fermer -->
            <div class="ml-4 flex-shrink-0 flex">
                <button onclick="hideToast()" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Barre de progression -->
        <div class="h-1 bg-gray-100 rounded-b-lg overflow-hidden">
            <div id="toast-progress" class="h-full bg-gradient-to-r from-indigo-500 to-purple-600 transition-all duration-75 ease-linear" style="width: 100%"></div>
        </div>
    </div>

    <script>
        function showToast() {
            const toast = document.getElementById('toast-notification');
            const progress = document.getElementById('toast-progress');
            
            if (toast && progress) {
                // Afficher le toast
                setTimeout(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                    toast.classList.add('translate-x-0', 'opacity-100');
                }, 100);
                
                // Animation de la barre de progression
                let width = 100;
                const interval = setInterval(() => {
                    width -= 0.5;
                    progress.style.width = width + '%';
                    
                    if (width <= 0) {
                        clearInterval(interval);
                        hideToast();
                    }
                }, 50); // 5 secondes au total
            }
        }
        
        function hideToast() {
            const toast = document.getElementById('toast-notification');
            if (toast) {
                toast.classList.remove('translate-x-0', 'opacity-100');
                toast.classList.add('translate-x-full', 'opacity-0');
                
                // Supprimer l'élément après l'animation
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }
        
        // Afficher le toast au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            showToast();
        });
    </script>
@endif
