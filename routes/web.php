<?php

// use App\Http\Controllers\LivreController;
// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('Layout');
// });
// Route::get('/route_test', function () {
//     return view('test');
// });
// Route::get('/routeLivres', function () {
//     return view('Livres');
// });

// // Definition de la route TestVueController
// Route::get('/routetestvue', [LivreController::class, 'index']);
// // Route::get('/route_layout', function () {
// //     return view('layout');
// // });
// *****************************************************************

use App\Http\Controllers\LivreController;
use App\Http\Controllers\EditController;
use App\Http\Controllers\GestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Routes d'authentification
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard')->middleware('auth');
// profile utilisateur
// Route::get('/profile', [AuthController::class, 'profile'])->name('profile')->middleware('auth');
Route::get('/profile', [UtilisateurController::class, 'profile'])->name('profile')->middleware('auth');

Route::get('/route_test', function () {
    return view('test');
});

Route::get('/routeLivres', function () {
    return view('Livres');
})->middleware('authcheck');
Route::get('/ajoutOuvrages', function () {
    return view('ajoutOuvrages');
})->middleware('authcheck');


Route::get('/routetestvue', [LivreController::class, 'index']);

// Nouvelles routes pour la section Edit
Route::get('/routeEditDesc', [EditController::class, 'getLivres'])->middleware('authcheck');
// Route::get('/routeValidComment', [EditController::class, 'validateComments']);
Route::post('/ajout_ouvrage', [EditController::class, 'store'])->name('ajout_ouvrage.store')->middleware('authcheck');
Route::get('/routeAjoutCat', [EditController::class, 'getCategories'])->name('routeAjoutCat.getCategories');
Route::delete('/routeSupprimerOuvrage/{id}', [EditController::class, 'destroy'])->name('routeSupprimerOuvrage.destroy');
Route::get('/routeModifierOuvrage/{id}', [EditController::class, 'edit'])->name('routeModifierOuvrage.edit');
Route::post('/routeModifierOuvrage/{id}', [EditController::class, 'update'])->name('routeModifierOuvrage.update');
Route::get('/ouvrages/{ouvrage}', [EditController::class, 'apiShow'])->name('ouvrages.apiShow');

// Route pour renvoyer les données d'un ouvrage au format JSON
Route::get('/ouvrage/{id}', [EditController::class, 'apiShow'])->name('ouvrages.show');
// Classification des ouvrages
Route::get('/categories/create', [CategoriesController::class, 'create'] )->name('categories.create');
Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
Route::get('/classification', [CategoriesController::class, 'index'])->name('classification.index');
Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
Route::get('/categories/create', [CategoriesController::class, 'create'])->name('categories.create');
Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
Route::get('/categories/{id}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
Route::post('/categories/{id}', [CategoriesController::class, 'update'])->name('categories.update');
Route::delete('/categories/{id}', [CategoriesController::class, 'destroy'])->name('categories.destroy');



// Routes pour la gestion des commentaires
Route::prefix('comments')->name('comments.')->group(function () {
    Route::get('/', [CommentaireController::class, 'index'])->name('index');
    Route::get('/{commentaire}', [CommentaireController::class, 'show'])->name('show');
    Route::post('/{commentaire}/approuve', [CommentaireController::class, 'approuve'])->name('approuve');
    Route::post('/{commentaire}/reject', [CommentaireController::class, 'reject'])->name('reject');
});



// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    // Routes d'administration
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/maintien-site', [AdminController::class, 'maintenirSite'])->name('admin.maintien');
        Route::get('/gestion-users', [AdminController::class, 'gererUtilisateurs'])->name('admin.users');
    });

    // Routes de gestion
    Route::prefix('gestion')->group(function () {
        // Gestion du catalogue
        Route::get('/catalogue', [GestController::class, 'gererCatalogue'])->name('gestion.catalogue');

        // Gestion des stocks
        Route::prefix('stocks')->name('stocks.')->group(function () {
            Route::get('/', [StockController::class, 'index'])->name('index');
            Route::get('/create', [StockController::class, 'create'])->name('create');
            Route::post('/', [StockController::class, 'store'])->name('store');
            Route::get('/{stock}', [StockController::class, 'show'])->name('show');
            Route::get('/{stock}/edit', [StockController::class, 'edit'])->name('edit');
            Route::put('/{stock}', [StockController::class, 'update'])->name('update');
        });

        // Suivi des ventes
        Route::prefix('ventes')->name('ventes.')->group(function () {
            Route::get('/', [GestController::class, 'index'])->name('index');
            Route::get('/{lignevente}', [GestController::class, 'show'])->name('show');
        });
    });

    // Routes des commentaires
    Route::prefix('comments')->name('comments.')->group(function () {
        Route::get('/', [CommentaireController::class, 'index'])->name('index');
        Route::get('/{commentaire}', [CommentaireController::class, 'show'])->name('show');
        Route::post('/{commentaire}/approuve', [CommentaireController::class, 'approuve'])->name('approuve');
        Route::post('/{commentaire}/reject', [CommentaireController::class, 'reject'])->name('reject');
    });
});

// Front-office
Route::get('/', [PublicController::class, 'home'])->name('accueil');
Route::get('/produits', [PublicController::class, 'produits'])->name('produits');
Route::get('/produits/{id}', [PublicController::class, 'details'])->name('produit.details');
Route::get('/login', [PublicController::class, 'login'])->name('login');
Route::get('/register', [PublicController::class, 'register'])->name('register');


Auth::routes();
