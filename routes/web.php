<?php
use App\Http\Controllers\{
    LivreController, EditController, GestController, AdminController,
    AuthController, CategoriesController, CommentaireController,
    PublicController, UtilisateurController, StockController
};
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Accueil = redirection vers login
Route::get('/', fn() => redirect()->route('login'));

// Authentification
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard')->middleware('auth');
// Route::get('/profile', [UtilisateurController::class, 'profileByRole'])->name('profile')->middleware('auth');

// --------------------- ADMIN ---------------------
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    Route::get('/profile', [UtilisateurController::class, 'profileByRole'])->name('profile');
    Route::get('/maintien-site', [AdminController::class, 'maintenirSite'])->name('maintien');
    Route::get('/gestion-users', [AdminController::class, 'gererUtilisateurs'])->name('users');
});

// --------------------- GESTIONNAIRE ---------------------
Route::middleware(['auth', 'isGestionnaire'])->prefix('gestion')->name('gestion.')->group(function () {
    Route::get('/catalogue', [GestController::class, 'gererCatalogue'])->name('catalogue');

    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::get('/create', [StockController::class, 'create'])->name('create');
        Route::post('/', [StockController::class, 'store'])->name('store');
        Route::get('/{stock}', [StockController::class, 'show'])->name('show');
        Route::get('/{stock}/edit', [StockController::class, 'edit'])->name('edit');
        Route::put('/{stock}', [StockController::class, 'update'])->name('update');
    });

    Route::prefix('ventes')->name('ventes.')->group(function () {
        Route::get('/', [GestController::class, 'index'])->name('index');
        Route::get('/{lignevente}', [GestController::class, 'show'])->name('show');
    });
});

// --------------------- CLIENT ---------------------
Route::middleware(['auth', 'isClient'])->prefix('frontOffice')->name('frontOffice.')->group(function () {
    Route::get('/', [PublicController::class, 'home'])->name('home');
    Route::get('/profile', [UtilisateurController::class, 'profileByRole'])->name('profile');
    Route::get('/ouvrages', [PublicController::class, 'ouvrages'])->name('ouvrages');
    Route::get('/ouvrages/{id}', [PublicController::class, 'details'])->name('ouvrage.details');
    Route::get('/livres/{id}/favoris', [LivreController::class, 'favoris'])->name('livres.favoris');
    Route::get('/livres/{id}', [LivreController::class, 'show'])->name('livres.show');
});

// --------------------- ÉDITEURS + ADMIN ---------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/routeEditDesc', [EditController::class, 'getLivres']);
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
Route::get('/accueil', [PublicController::class, 'home'])->name('accueil');
Route::get('/ouvrages', [PublicController::class, 'ouvrages'])->name('ouvrages');
Route::get('/ouvrages/{id}', [PublicController::class, 'details'])->name('ouvrage.details');
Route::get('/livres/{id}/favoris', [LivreController::class, 'favoris'])->name('livres.favoris');
Route::get('/livres/{id}', [LivreController::class, 'show'])->name('livres.show');

// Auth routes
Auth::routes();
