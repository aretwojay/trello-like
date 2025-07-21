<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');
 
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'loginView')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::get('/register', 'registerView')->name('register');
    Route::post('/register', 'register')->name('register.post');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/forgot-password', 'forgotPasswordView')->name('password.request');
    Route::post('/forgot-password', 'forgotPassword')->name('password.email');
    
    // Routes de vérification d'email
    Route::get('/email/verify', 'verificationNotice')->middleware('auth')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'verifyEmail')->middleware(['auth', 'signed'])->name('verification.verify');
    Route::post('/email/verification-notification', 'resendVerificationEmail')->middleware(['auth', 'throttle:6,1'])->name('verification.send');
    Route::get('/email/verification-status', 'checkVerificationStatus')->middleware('auth')->name('verification.check');
    
    // Route de test (développement uniquement)
    Route::get('/test-email', 'testEmail')->middleware('auth')->name('test.email');
    Route::get('/test-resend', function() {
        if (!app()->environment('local')) {
            abort(404);
        }
        
        try {
            // Créer un utilisateur de test avec un ID
            $testUser = new \App\Models\User([
                'name' => 'Test User',
                'email' => "rubenmuya9129@gmail.com"
            ]);
            // Simuler un ID pour les tests
            $testUser->id = 999;
            
            // Test de la configuration (sans générer l'URL)
            return response()->json([
                'success' => true,
                'message' => 'Configuration Resend testée avec succès !',
                'config' => [
                    'mail_mailer' => config('mail.default'),
                    'from_address' => config('mail.from.address'),
                    'from_name' => config('mail.from.name'),
                    'resend_key_set' => !empty(env('RESEND_API_KEY')),
                    'app_env' => app()->environment(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    })->name('test.resend');
    
    Route::get('/test-mail-direct', function() {
        if (!app()->environment('local')) {
            abort(404);
        }
        
        try {
            // Import des facades nécessaires
            $log = \Illuminate\Support\Facades\Log::class;
            $mail = \Illuminate\Support\Facades\Mail::class;
            
            $log::info('Testing direct mail send...');
            
            $mail::raw('Ceci est un test d\'envoi direct avec Resend depuis Laravel.', function ($message) {
                $message->to('rubenmuya9129@gmail.com')
                       ->subject('Test Resend Direct - ' . now());
            });
            
            $log::info('Mail sent successfully');
            
            return response()->json([
                'success' => true,
                'message' => 'Email envoyé directement via Resend',
                'timestamp' => now()->toISOString(),
                'config' => [
                    'mailer' => config('mail.default'),
                    'from_address' => config('mail.from.address')
                ]
            ]);
        } catch (\Exception $e) {
            $log::error('Direct mail error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'config_debug' => [
                    'mail_mailer' => config('mail.default'),
                    'resend_api_key_exists' => !empty(env('RESEND_API_KEY')),
                    'from_address' => config('mail.from.address'),
                ]
            ], 500);
        }
    })->name('test.mail.direct');
});

Route::middleware(['auth'])->group(function () {
    Route::controller(ProjectController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/projects', 'index')->name('projects.index');
        Route::get('/projects/create', 'create')->name('projects.create');
        Route::post('/projects', 'store')->name('projects.store');
        Route::get('/projects/{project}', 'show')->name('projects.show');
        Route::get('/projects/{project}/edit', 'edit')->name('projects.edit');
        Route::put('/projects/{project}', 'update')->name('projects.update');
        Route::delete('/projects/{project}', 'destroy')->name('projects.destroy');
        Route::post('/projects/{project}/members', 'addMember')->name('projects.members.add');
        Route::delete('/projects/{project}/members', 'removeMember')->name('projects.members.remove');
        Route::get('/projects/{project}/statistics', 'statistics')->name('projects.statistics');
        Route::post('/projects/{project}/duplicate', 'duplicate')->name('projects.duplicate');
    });

    // Routes pour les tâches
    Route::controller(TaskController::class)->group(function () {
        Route::get('/tasks/create', 'create')->name('tasks.create');
        Route::post('/tasks', 'store')->name('tasks.store');
        Route::get('/tasks/{task}/edit', 'edit')->name('tasks.edit');
        Route::put('/tasks/{task}', 'update')->name('tasks.update');
        Route::delete('/tasks/{task}', 'destroy')->name('tasks.destroy');
        Route::put('/tasks/{task}/move', 'move')->name('tasks.move');
        Route::put('/tasks/{task}/toggle-complete', 'toggleComplete')->name('tasks.toggle-complete');
        Route::post('/tasks/{task}/duplicate', 'duplicate')->name('tasks.duplicate');
    });
});