<!-- Styles pour le modal unifié -->
<style>
/* Animation d'entrée du modal */
@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

[data-modal-open] .bg-white {
    animation: modalFadeIn 0.2s ease-out;
}

/* Style pour les checkboxes utilisateurs */
input[name="assigned_users[]"]:checked + div {
    background-color: rgb(238 242 255);
    border-color: rgb(199 210 254);
}

/* Style pour l'état focus des inputs */
.modal-form input:focus,
.modal-form select:focus,
.modal-form textarea:focus {
    box-shadow: 0 0 0 3px rgb(129 140 248 / 0.1);
}

/* Style pour les labels requis */
.modal-form label:has(+ input[required])::after,
.modal-form label:has(+ select[required])::after {
    content: " *";
    color: rgb(239 68 68);
}

/* Animation pour le spinner de chargement */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Style pour les messages d'erreur */
.text-red-600 {
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 20%, 50%, 80%, 100% {
        transform: translateX(0);
    }
    10%, 30%, 70%, 90% {
        transform: translateX(-2px);
    }
    40%, 60% {
        transform: translateX(2px);
    }
}

/* Amélioration de l'accessibilité */
.modal-form input[aria-invalid="true"],
.modal-form select[aria-invalid="true"],
.modal-form textarea[aria-invalid="true"] {
    border-color: rgb(239 68 68);
    box-shadow: 0 0 0 1px rgb(239 68 68);
}

/* Style pour le backdrop blur */
[data-modal-open] {
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

/* Responsive adjustments */
@media (max-width: 640px) {
    [data-modal-open] .max-w-lg {
        max-width: calc(100vw - 2rem);
        margin: 1rem;
    }
}
</style>
