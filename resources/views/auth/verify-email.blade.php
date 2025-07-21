@extends('layout.auth')

@section('title', 'Vérifier votre email')

@section('content')
<div class="text-center mb-6">
    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
        </svg>
    </div>
    <h2 class="mt-4 text-3xl font-extrabold text-gray-900">
        Vérifiez votre email
    </h2>
    <p class="mt-2 text-sm text-white">
        Nous avons envoyé un lien de vérification à votre adresse email
    </p>
</div>

<div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-yellow-800">
                Email de vérification requis
            </h3>
            <div class="mt-2 text-sm text-yellow-700">
                <p>Votre email <strong>{{ Auth::user()->email }}</strong> doit être vérifié avant d'accéder à l'application.</p>
            </div>
        </div>
    </div>
</div>

<div class="space-y-4">
    <!-- Instructions -->
    <div class="text-sm text-white">
        <ol class="list-decimal list-inside space-y-2">
            <li>Consultez votre boîte email (y compris les spams)</li>
            <li>Cliquez sur le lien de vérification dans l'email</li>
            <li>Vous serez automatiquement connecté</li>
        </ol>
    </div>

    <!-- Resend Email Form -->
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Renvoyer l'email de vérification
        </button>
    </form>

    <!-- Change Email Option -->
    <div class="text-center">
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-sm text-white">
                Se connecter avec un autre compte
            </button>
        </form>
    </div>
</div>

<!-- Help Section -->
<div class="mt-8 bg-gray-50 rounded-lg p-4">
    <h4 class="text-sm font-medium text-white mb-2">Vous ne recevez pas l'email ?</h4>
    <div class="text-sm text-gray-600 space-y-1">
        <p>• Vérifiez votre dossier spam/courrier indésirable</p>
        <p>• Assurez-vous que votre adresse email est correcte</p>
        <p>• Attendez quelques minutes avant de renvoyer</p>
        <p>• Vérifiez que votre boîte email n'est pas pleine</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh après vérification (si l'utilisateur revient sur cette page)
    if (document.hidden !== undefined) {
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                // Vérifier si l'email a été vérifié
                fetch('{{ route("verification.check") }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.verified) {
                            window.location.href = '/dashboard';
                        }
                    })
                    .catch(() => {
                        // Ignorer les erreurs silencieusement
                    });
            }
        });
    }
});
</script>
@endsection
