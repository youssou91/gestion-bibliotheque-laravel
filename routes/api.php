<?php

use App\Http\Controllers\{
    AIController,
    AmendeController,
    CommentaireController,
    EmpruntController,
    PayPalController,
    PublicController,
    ReservationController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Routes API pour l'application
|
*/

// Routes d'authentification
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::post('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.reset');

// Routes publiques
Route::get('/livres', [PublicController::class, 'apiLivres']);
Route::get('/livres/{id}', [PublicController::class, 'apiLivre']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function() {
    // Utilisateur connecté
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Commentaires
    Route::prefix('commentaires')->group(function() {
        Route::post('/', [CommentaireController::class, 'store']);
        Route::put('/{id}', [CommentaireController::class, 'update']);
        Route::delete('/{id}', [CommentaireController::class, 'destroy']);
        Route::post('/{commentaire}/approuve', [CommentaireController::class, 'approuve']);
        Route::post('/{commentaire}/reject', [CommentaireController::class, 'reject']);
    });
    
    // Réservations
    Route::apiResource('reservations', ReservationController::class);
    
    // Emprunts
    Route::apiResource('emprunts', EmpruntController::class);
    
    // Paiements
    Route::prefix('paiements')->group(function() {
        Route::post('/paypal', [PayPalController::class, 'createPayment']);
        Route::post('/webhook', [PayPalController::class, 'handleWebhook']);
        Route::post('/stripe', [AmendeController::class, 'initierPaiementStripe']);
    });

    // IA
    Route::prefix('ai')->group(function() {
        Route::post('/ask', [AIController::class, 'ask']);
        Route::get('/agents', [AIController::class, 'listAgents']);
    });
});

// Webhook Stripe (sans authentification)
Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handleWebhook'])
    ->name('stripe.webhook')
    ->withoutMiddleware(['csrf']);
