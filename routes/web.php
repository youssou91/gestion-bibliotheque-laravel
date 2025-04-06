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

Route::get('/routetestvue', [LivreController::class, 'index']);

// Nouvelles routes pour la section Edit
Route::get('/routeEditDesc', [EditController::class, 'editDescriptions']);
Route::get('/routeClassOuvrage', [EditController::class, 'classifyOuvrages']);
Route::get('/routeValidComment', [EditController::class, 'validateComments']);

// Nouvelles routes pour la section Gest
Route::get('/routeGestCatalog', [GestController::class, 'gererCatalogue']);
Route::get('/routeGestStock', [GestController::class, 'gererStock']);
Route::get('/routeSuiviVente', [GestController::class, 'suivreVentes']);

// Nouvelles routes pour la section Admin
Route::get('/routeMaintienSite', [AdminController::class, 'maintenirSite']);
Route::get('/routeGestusers', [AdminController::class, 'gererUtilisateurs']);