@extends('layout.auth')

@section('title', 'Connexion')

@section('content')
<div class="bg-white rounded-2xl shadow-2xl w-full p-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-sign-in-alt text-white text-2xl"></i>
        </div>
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Bon retour !</h2>
        <p class="text-gray-600">Connectez-vous à votre compte</p>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <i class="fas fa-exclamation-circle text-red-400 mt-0.5 mr-3"></i>
                <div>
                    <h3 class="text-sm font-medium text-red-800">Erreur de connexion :</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
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
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror" 
                   placeholder="votre@email.com"
                   required
                   autocomplete="email"
                   autofocus>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Field -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2 text-gray-500"></i>Mot de passe
            </label>
            <div class="relative">
                <input type="password" 
                       id="password" 
                       name="password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('password') border-red-500 @enderror" 
                       placeholder="Entrez votre mot de passe"
                       required
                       autocomplete="current-password">
                <button type="button" 
                        id="togglePassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <i class="fas fa-eye text-gray-500 hover:text-gray-700"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input type="checkbox" 
                       id="remember" 
                       name="remember"
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-gray-600">
                    Se souvenir de moi
                </label>
            </div>
            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-500 underline">
                Mot de passe oublié ?
            </a>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 transform hover:scale-105">
            <i class="fas fa-sign-in-alt mr-2"></i>
            Se connecter
        </button>
    </form>

    <!-- Register Link -->
    <div class="mt-8 text-center">
        <p class="text-gray-600">
            Vous n'avez pas encore de compte ?
            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500 font-semibold underline">
                Créer un compte
            </a>
        </p>
    </div>

    <!-- Social Login -->
    <div class="mt-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Ou continuer avec</span>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-3">
            <button class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition duration-200">
                <i class="fab fa-google text-red-500 mr-2"></i>
                Google
            </button>
            <button class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition duration-200">
                <i class="fab fa-github mr-2"></i>
                GitHub
            </button>
        </div>
    </div>

    <!-- Demo Credentials (optionnel, pour développement) -->
    <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <h4 class="text-sm font-medium text-gray-700 mb-2">
            <i class="fas fa-info-circle mr-2"></i>Comptes de démonstration
        </h4>
        <div class="text-xs text-gray-600 space-y-1">
            <div class="flex justify-between">
                <span>Admin :</span>
                <span class="font-mono">admin@example.com / password</span>
            </div>
            <div class="flex justify-between">
                <span>Utilisateur :</span>
                <span class="font-mono">user@example.com / password</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    // Auto-fill demo credentials
    document.addEventListener('DOMContentLoaded', function() {
        const demoCredentials = document.querySelectorAll('[data-demo]');
        demoCredentials.forEach(function(element) {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                const email = this.dataset.email;
                const password = this.dataset.password;
                
                document.getElementById('email').value = email;
                document.getElementById('password').value = password;
            });
        });
    });

    // Form submission loading state
    document.querySelector('form').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Connexion en cours...';
        submitBtn.disabled = true;
        
        // Réactiver si la soumission échoue (par exemple, erreur de validation)
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }, 5000);
    });
</script>
@endpush
