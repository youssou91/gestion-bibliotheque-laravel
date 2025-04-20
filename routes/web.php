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
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CommentaireController;
use Illuminate\Support\Facades\Route;

// Routes existantes
Route::get('/', function () {
    return view('Layout');
});

Route::get('/route_test', function () {
    return view('test');
});

Route::get('/routeLivres', function () {
    return view('Livres');
});
Route::get('/ajoutOuvrages', function () {
    return view('ajoutOuvrages');
});


Route::get('/routetestvue', [LivreController::class, 'index']);

// Nouvelles routes pour la section Edit
Route::get('/routeEditDesc', [EditController::class, 'getLivres']);
// Route::get('/routeValidComment', [EditController::class, 'validateComments']);
Route:: post('/ajout_ouvrage', [EditController::class, 'store'])->name('ajout_ouvrage.store');
Route::get('/routeAjoutCat', [EditController::class, 'getCategories'])->name('routeAjoutCat.getCategories');
Route::delete('/routeSuppression/{id}', [EditController::class, 'destroy'])->name('routeSuppression.destroy');
Route::get('/routeModifierOuvrage/{id}', [EditController::class, 'edit'])->name('routeModifierOuvrage.edit');
Route::post('/routeModifierOuvrage/{id}', [EditController::class, 'update'])->name('routeModifierOuvrage.update');
// Route pour renvoyer les données d'un ouvrage au format JSON
Route::get('/ouvrage/{id}', [EditController::class, 'show'])->name('ouvrages.show');
// Classification des ouvrages
Route::get('/categories/create', [CategoriesController::class, 'create'] )->name('categories.create');
Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
Route::get('/classification', [CategoriesController::class, 'classifyOuvrages'])->name('classification.index');
Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
Route::get('/categories/create', [CategoriesController::class, 'create'])->name('categories.create');
Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
Route::get('/categories/{id}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
Route::post('/categories/{id}', [CategoriesController::class, 'update'])->name('categories.update');
Route::delete('/categories/{id}', [CategoriesController::class, 'destroy'])->name('categories.destroy');



// Page principale de validation
Route::get('/comments', [CommentaireController::class, 'index'])->name('comments.index');
Route::get('/comments/{commentaire}', [CommentaireController::class, 'show'])->name('comments.show');
Route::post('/comments/{commentaire}/approuve', [CommentaireController::class, 'approuve'])->name('comments.approuve');
Route::post('/comments/{commentaire}/rejete', [CommentaireController::class, 'rejete'])->name('comments.rejete');



// Nouvelles routes pour la section Gest
Route::get('/routeGestCatalog', [GestController::class, 'gererCatalogue']);
Route::get('/routeGestStock', [GestController::class, 'gererStock']);
Route::get('/routeSuiviVente', [GestController::class, 'suivreVentes']);

// Nouvelles routes pour la section Admin
Route::get('/routeMaintienSite', [AdminController::class, 'maintenirSite']);
Route::get('/routeGestusers', [AdminController::class, 'gererUtilisateurs']);