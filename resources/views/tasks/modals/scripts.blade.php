<!-- Script pour gérer le modal unifié -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fermer le modal en appuyant sur Échap
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const activeModal = document.querySelector('[data-modal-open]');
            if (activeModal) {
                window.location.href = '{{ route('projects.show', $project) }}';
            }
        }
    });

    // Auto-resize pour les textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        // Auto-resize initial
        function autoResize() {
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';
        }
        
        // Appliquer la taille initiale
        setTimeout(autoResize, 10);
        
        // Écouter les changements
        textarea.addEventListener('input', autoResize);
        textarea.addEventListener('paste', () => setTimeout(autoResize, 10));
    });

    // Amélioration de l'UX pour les formulaires de modal
    const modalForm = document.querySelector('.modal-form');
    if (modalForm) {
        modalForm.addEventListener('submit', function() {
            const submitButton = modalForm.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                const originalText = submitButton.textContent;
                submitButton.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Traitement...
                `;
                
                // Restaurer le bouton si il y a une erreur
                setTimeout(() => {
                    if (submitButton.disabled) {
                        submitButton.disabled = false;
                        submitButton.textContent = originalText;
                    }
                }, 5000);
            }
        });
    }

    // Animation des checkboxes avec état initial
    const userCheckboxes = document.querySelectorAll('input[name="assigned_users[]"]');
    userCheckboxes.forEach(checkbox => {
        function updateCheckboxStyle() {
            const label = checkbox.closest('label');
            if (checkbox.checked) {
                label.classList.add('bg-indigo-50', 'ring-1', 'ring-indigo-200');
            } else {
                label.classList.remove('bg-indigo-50', 'ring-1', 'ring-indigo-200');
            }
        }
        
        // Appliquer l'état initial
        updateCheckboxStyle();
        
        // Écouter les changements
        checkbox.addEventListener('change', updateCheckboxStyle);
    });

    // Validation en temps réel pour les champs requis
    const requiredInputs = document.querySelectorAll('input[required], select[required]');
    requiredInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                this.classList.remove('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
            } else {
                this.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                this.classList.add('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
            }
        });
    });

    // Focus sur le premier champ lors de l'ouverture du modal
    const titleInput = document.querySelector('input[name="title"]');
    if (titleInput) {
        setTimeout(() => {
            titleInput.focus();
            titleInput.select();
        }, 100);
    }
});
</script>
