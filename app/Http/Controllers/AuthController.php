<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use App\Mail\WelcomeEmail;



class AuthController extends Controller
{
    public function loginView()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function registerView()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        // Déclencher l'événement d'inscription pour envoyer l'email de vérification
        event(new Registered($user));

        Auth::login($user);


        return redirect()->route("dashboard")
            ->with('success', 'Compte créé avec succès ! Veuillez vérifier votre email.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function forgotPasswordView()
    {
        return view('auth.forgot-password');
    }

    /**
     * Afficher la page de notification de vérification d'email
     */
    public function verificationNotice()
    {
        return view('auth.verify-email');
    }

    /**
     * Vérifier l'email via le lien cliqué
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();

        // Envoyer l'email de bienvenue après vérification
        Mail::to($request->user())->send(new WelcomeEmail($request->user()));

        return redirect('/dashboard')
            ->with('success', 'Email vérifié avec succès ! Bienvenue sur notre plateforme 🎉');
    }

    /**
     * Renvoyer l'email de vérification
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/dashboard')
                ->with('info', 'Votre email est déjà vérifié.');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()
            ->with('success', 'Lien de vérification renvoyé !');
    }

    /**
     * Vérifier le statut de vérification (pour AJAX)
     */
    public function checkVerificationStatus(Request $request)
    {
        return response()->json([
            'verified' => $request->user()->hasVerifiedEmail()
        ]);
    }

    /**
     * Test l'envoi d'email (uniquement en développement)
     */
    public function testEmail(Request $request)
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        try {
            // Test envoi email de bienvenue
            Mail::to($user)->send(new WelcomeEmail($user));
            
            return response()->json([
                'success' => true,
                'message' => 'Emails de test envoyés avec succès !',
                'email' => $user->email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi : ' . $e->getMessage()
            ], 500);
        }
    }
}