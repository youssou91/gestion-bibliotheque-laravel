<?php

use App\Http\Controllers\{
    LivreController,
    EditController,
    GestController,
    AdminController,
    AmendeController,
    AuthController,
    CategoriesController,
    CommentaireController,
    EmpruntController,
    FavoriController,
    PublicController,
    ReservationController,
    UtilisateurController,
    StockController
};
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Accueil = redirection vers login
// Route::get('/', fn() => redirect()->route('login'));
// Accueil général — si utilisateur connecté, redirection selon rôle, sinon vers login
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'gestionnaire':
                return redirect()->route('gestion.catalogue');
            case 'client':
                return redirect()->route('frontOffice.accueil');
            default:
                return redirect()->route('login');
        }
    }
    return redirect()->route('login');
});


// ------------------ Authentification ---------------------------
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard')->middleware('auth');

// --------------------- ADMIN ---------------------
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [GestController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/profile', [UtilisateurController::class, 'profileByRole'])->name('profile');
    Route::get('/maintien-site', [AdminController::class, 'maintenirSite'])->name('maintien');
    Route::get('/utilisateurs', [UtilisateurController::class, 'index'])->name('utilisateurs');
    Route::get('/emprunts', [EmpruntController::class, 'index'])->name('emprunts.index');
    Route::get('/commentaires', [CommentaireController::class, 'index'])->name('commentaires');
    Route::post('/{commentaire}/approuve', [CommentaireController::class, 'approuve'])->name('approuve');
    Route::post('/{commentaire}/reject', [CommentaireController::class, 'reject'])->name('reject');
    Route::get('/utilisateurs/create', [UtilisateurController::class, 'create'])->name('utilisateurs.create');
    Route::post('/utilisateurs', [UtilisateurController::class, 'store'])->name('utilisateurs.store');
    Route::put('/utilisateurs/{id}', [UtilisateurController::class, 'update'])->name('utilisateurs.update');
    Route::patch('/utilisateurs/{id}/toggle-status', [UtilisateurController::class, 'toggleStatus'])->name('utilisateurs.toggle-status');
    Route::get('/classification', [CategoriesController::class, 'classifyOuvrages'])->name('classification');
    Route::get('reservations', [ReservationController::class, 'indexAdmin'])->name('reservations');
    Route::post('reservations/{id}/valider', [ReservationController::class, 'validerAdmin'])->name('reservations.valider');
    Route::post('reservations/{id}/annuler', [ReservationController::class, 'annulerAdmin'])->name('reservations.annuler');

    Route::get('/amendes', [AmendeController::class, 'index'])->name('amendes');

});

// --------------------- GESTIONNAIRE ---------------------
Route::middleware(['auth', 'isGestionnaire'])->prefix('gestion')->name('gestion.')->group(function () {
    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::get('/create', [StockController::class, 'create'])->name('create');
        Route::post('/', [StockController::class, 'store'])->name('store');
        Route::get('/{stock}', [StockController::class, 'show'])->name('show');
        Route::get('/{stock}/edit', [StockController::class, 'edit'])->name('edit');
        Route::put('/{stock}', [StockController::class, 'update'])->name('update');
    });
});

// --------------------- CLIENT ---------------------
Route::middleware(['auth', 'isClient'])->prefix('frontOffice')->name('frontOffice.')->group(function () {
    Route::get('/accueil', [PublicController::class, 'clientDashboard'])->name('accueil');
    Route::get('/profile', [UtilisateurController::class, 'profileByRole'])->name('profile');
    Route::post('/ouvrages/{id}/commenter', [CommentaireController::class, 'store'])->name('ouvrages.commenter')->middleware('auth');
    Route::post('/emprunts/store', [EmpruntController::class, 'store'])->name('emprunts.store')->middleware('auth');
    Route::get('/mes-emprunts', [EmpruntController::class, 'mesEmprunts'])->name('emprunts');
    Route::post('/emprunts/{id}/retour', [EmpruntController::class, 'retour'])->name('emprunts.retour');
    // Route::post('/emprunts/retour/{id}', [EmpruntController::class, 'retour'])->name('frontOffice.emprunts.retour');
    Route::get('/mes-favoris', [FavoriController::class, 'index'])->name('favoris');
    Route::post('/favoris/{ouvrage}', [FavoriController::class, 'ajouter'])->name('favoris.ajouter');
    Route::delete('/favoris/{ouvrage}', [FavoriController::class, 'retirer'])->name('favoris.retirer');
    Route::get('/ouvrages', [PublicController::class, 'ouvrages'])->name('ouvrages');
    Route::get('/ouvrages/{id}', [PublicController::class, 'details'])->name('ouvrage.details');
    Route::get('/livres/{id}/favoris', [LivreController::class, 'favoris'])->name('livres.favoris');
    Route::post('/updateAvatar', [UtilisateurController::class, 'updateAvatar'])->name('updateAvatar');
    Route::post('/updatePassword', [UtilisateurController::class, 'updatePassword'])->name('updatePassword');

    Route::get('/livres/{id}', [LivreController::class, 'show'])->name('livres.show');


    Route::post('/reserver', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/mes-reservations', [ReservationController::class, 'index'])->name('reservations');
    Route::post('/mes-reservations/{reservation}/annuler', [ReservationController::class, 'annuler'])->name('reservations.annuler');


    Route::post('/recuperer/{id}', [ReservationController::class, 'recuperer'])->name('reservations.recuperer');
});

// --------------------- ÉDITEURS + ADMIN ---------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/gestion/catalogues', [EditController::class, 'getLivres'])->name('gestion.catalogue');
    Route::get('/gestion/stock', [StockController::class, 'index'])->name('gestion.stock');
    Route::get('/gestion/ventes', [GestController::class, 'index'])->name('gestion.ventes');
    Route::post('/ajout_ouvrage', [EditController::class, 'store'])->name('ajout_ouvrage.store');
    Route::get('/routeAjoutCat', [EditController::class, 'getCategories'])->name('routeAjoutCat.getCategories');
    Route::delete('/routeSupprimerOuvrage/{id}', [EditController::class, 'destroy'])->name('routeSupprimerOuvrage.destroy');
    Route::get('/routeModifierOuvrage/{id}', [EditController::class, 'edit'])->name('routeModifierOuvrage.edit');
    Route::post('/routeModifierOuvrage/{id}', [EditController::class, 'update'])->name('routeModifierOuvrage.update');
    Route::get('/ouvrages/{ouvrage}', [EditController::class, 'apiShow'])->name('ouvrages.apiShow');
});

// --------------------- CATÉGORIES ---------------------
Route::middleware(['auth'])->prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [CategoriesController::class, 'index'])->name('index');
    Route::get('/create', [CategoriesController::class, 'create'])->name('create');
    Route::post('/', [CategoriesController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [CategoriesController::class, 'edit'])->name('edit');
    Route::post('/{id}', [CategoriesController::class, 'update'])->name('update');
    Route::delete('/{id}', [CategoriesController::class, 'destroy'])->name('destroy');
});

// --------------------- COMMENTAIRES ---------------------
Route::middleware(['auth'])->prefix('comments')->name('comments.')->group(function () {
    Route::get('/', [CommentaireController::class, 'index'])->name('index');
    Route::get('/{commentaire}', [CommentaireController::class, 'show'])->name('show');
    Route::post('/{commentaire}/approuve', [CommentaireController::class, 'approuve'])->name('approuve');
    Route::post('/{commentaire}/reject', [CommentaireController::class, 'reject'])->name('reject');
});

// --------------------- TEST + PUBLIC ---------------------
Route::get('/route_test', fn() => view('test'));
Route::get('/routeLivres', fn() => view('Livres'))->middleware('auth');
Route::get('/ajoutOuvrages', fn() => view('ajoutOuvrages'))->middleware('auth');
Route::get('/routetestvue', [LivreController::class, 'index']);

// --------------------- FRONT PUBLIC (fallback) ---------------------
Route::get('/ouvrages', [PublicController::class, 'ouvrages'])->name('ouvrages');
Route::get('/ouvrages/{id}', [PublicController::class, 'details'])->name('ouvrage.details');
Route::get('/livres/{id}/favoris', [LivreController::class, 'favoris'])->name('livres.favoris');
Route::get('/livres/{id}', [LivreController::class, 'show'])->name('livres.show');

// Auth routes
Auth::routes();
