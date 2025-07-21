@extends('layout.auth')

@section('title', 'Mot de passe oublié')

@section('content')
<div class="bg-white rounded-2xl shadow-2xl w-full p-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="bg-gradient-to-r from-orange-500 to-red-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-key text-white text-2xl"></i>
        </div>
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Mot de passe oublié ?</h2>
        <p class="text-gray-600">Pas de problème ! Indiquez votre email et nous vous enverrons un lien de réinitialisation.</p>
    </div>

    <!-- Success Message -->
    @if (session('status'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <i class="fas fa-check-circle text-green-400 mt-0.5 mr-3"></i>
                <div>
                    <h3 class="text-sm font-medium text-green-800">Email envoyé !</h3>
                    <p class="mt-1 text-sm text-green-700">{{ session('status') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <i class="fas fa-exclamation-circle text-red-400 mt-0.5 mr-3"></i>
                <div>
                    <h3 class="text-sm font-medium text-red-800">Erreur :</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Reset Password Form -->
    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf
        
        <!-- Email Field -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-envelope mr-2 text-gray-500"></i>Adresse email
            </label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   value="{{ old('email') }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror" 
                   placeholder="Entrez votre adresse email"
                   required
                   autocomplete="email"
                   autofocus>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-xs text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Nous vous enverrons un lien sécurisé pour réinitialiser votre mot de passe.
            </p>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                id="submitBtn"
                class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-orange-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-200 transform hover:scale-105">
            <i class="fas fa-paper-plane mr-2"></i>
            Envoyer le lien de réinitialisation
        </button>
    </form>

    <!-- Back to Login -->
    <div class="mt-8 text-center">
        <a href="{{ route('login') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 text-sm font-medium transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour à la connexion
        </a>
    </div>

    <!-- Help Section -->
    <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
        <h4 class="text-sm font-medium text-blue-800 mb-2">
            <i class="fas fa-question-circle mr-2"></i>Besoin d'aide ?
        </h4>
        <div class="text-sm text-blue-700 space-y-2">
            <p>• Vérifiez votre dossier de courrier indésirable/spam</p>
            <p>• Le lien de réinitialisation expire dans 60 minutes</p>
            <p>• Vous pouvez demander un nouveau lien à tout moment</p>
        </div>
        <div class="mt-4 pt-3 border-t border-blue-200">
            <p class="text-sm text-blue-700">
                Toujours des problèmes ? 
                <a href="mailto:support@{{ config('app.url', 'example.com') }}" class="font-medium underline hover:no-underline">
                    Contactez le support
                </a>
            </p>
        </div>
    </div>

    <!-- Security Note -->
    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <div class="flex items-start">
            <i class="fas fa-shield-alt text-gray-500 mt-0.5 mr-3"></i>
            <div>
                <h5 class="text-sm font-medium text-gray-800">Note de sécurité</h5>
                <p class="mt-1 text-xs text-gray-600">
                    Pour des raisons de sécurité, nous ne confirmons pas si cette adresse email existe dans notre système. 
                    Si l'adresse est valide, vous recevrez un email de réinitialisation.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Form submission loading state
    document.querySelector('form').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Envoi en cours...';
        submitBtn.disabled = true;
        
        // Réactiver si la soumission échoue
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }, 10000); // 10 secondes de timeout
    });

    // Auto-hide success message after 10 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.querySelector('.bg-green-50');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.transition = 'opacity 0.5s ease-out';
                successMessage.style.opacity = '0';
                setTimeout(() => {
                    successMessage.remove();
                }, 500);
            }, 10000);
        }
    });

    // Email validation feedback
    document.getElementById('email').addEventListener('blur', function() {
        const email = this.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            this.classList.add('border-red-500');
            this.classList.remove('border-gray-300');
            
            // Add error message if not exists
            if (!this.parentNode.querySelector('.email-error')) {
                const errorMsg = document.createElement('p');
                errorMsg.className = 'mt-1 text-sm text-red-600 email-error';
                errorMsg.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i>Veuillez entrer une adresse email valide.';
                this.parentNode.appendChild(errorMsg);
            }
        } else {
            this.classList.remove('border-red-500');
            this.classList.add('border-gray-300');
            
            // Remove error message if exists
            const errorMsg = this.parentNode.querySelector('.email-error');
            if (errorMsg) {
                errorMsg.remove();
            }
        }
    });
</script>
@endpush
