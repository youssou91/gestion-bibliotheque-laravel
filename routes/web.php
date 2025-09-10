<?php

use App\Http\Controllers\{
    LivreController,
    EditController,
    GestController,
    AdminController,
    AIController,
    AmendeController,
    AuthController,
    CategoriesController,
    CommentaireController,
    EmpruntController,
    FavoriController,
    PayPalController,
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
Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
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
    Route::get('/classification/ajoutCategorie', [CategoriesController::class, 'create'])->name('classification.ajoutCategorie');
    Route::get('/classification/{id}/ModifierCategorie', [CategoriesController::class, 'edit'])->name('classification.ModifierCategorie');
    
    Route::get('reservations', [ReservationController::class, 'indexAdmin'])->name('reservations');
    Route::post('reservations/{id}/valider', [ReservationController::class, 'validerAdmin'])->name('reservations.valider');
    Route::post('reservations/{id}/annuler', [ReservationController::class, 'annulerAdmin'])->name('reservations.annuler');
    Route::get('/amendes', [AmendeController::class, 'index'])->name('amendes');
});

// --------------------- ADMIN STOCKS ---------------------
Route::middleware(['auth', 'isAdmin']) // Uniquement pour l'admin
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::prefix('stocks')
            ->name('stocks.')
            ->controller(StockController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('/{stock}/edit', 'edit')->name('edit');
                Route::get('/{stock}', 'show')->name('show');
                Route::put('/{stock}', 'update')->name('update');
                Route::delete('/{stock}', 'destroy')->name('destroy');
            });
    });

// --------------------- CLIENT ---------------------
Route::middleware(['auth', 'isClient'])->prefix('frontOffice')->name('frontOffice.')->group(function () {
    // Routes de paiement (redirigées vers l'API)
    Route::post('/paiement/retour', [PayPalController::class, 'handleReturn'])->name('paypal.return');
    Route::get('/paiement/succes', [AmendeController::class, 'succesPaiementStripe'])
        ->name('amende.paiement.succes');
    Route::get('/paiement/annule', [AmendeController::class, 'echecPaiementStripe'])
        ->name('amende.paiement.annule');
    
    Route::get('/accueil', [PublicController::class, 'clientDashboard'])->name('accueil');
    Route::get('/profile', [UtilisateurController::class, 'profileByRole'])->name('profile');
    Route::post('/ouvrages/{id}/commenter', [CommentaireController::class, 'store'])->name('ouvrages.commenter')->middleware('auth');
    Route::post('/emprunts/store', [EmpruntController::class, 'store'])->name('emprunts.store')->middleware('auth');
    Route::get('/mes-emprunts', [EmpruntController::class, 'mesEmprunts'])->name('emprunts');
    Route::post('/emprunts/{id}/retour', [EmpruntController::class, 'retour'])->name('emprunts.retour');
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
    Route::put('/mes-reservations/annuler/{id}', [ReservationController::class, 'annulerClient'])->name('reservations.annuler');
    Route::post('/recuperer/{id}', [ReservationController::class, 'recuperer'])->name('reservations.recuperer');
});

// --------------------- ADMINISTRATION ---------------------
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    // Route du tableau de bord admin
    Route::get('/dashboard', [GestController::class, 'adminDashboard'])->name('dashboard');
    
    // Redirection de la racine /admin vers le dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    
    // Routes pour les emprunts
    Route::prefix('emprunts')->name('emprunts.')->group(function () {
        Route::get('/', [GestController::class, 'adminDashboard']); // Utilisation de adminDashboard pour la liste des emprunts
        Route::post('/{emprunt}/retourner', [GestController::class, 'retournerEmprunt'])->name('retourner');
        Route::post('/{emprunt}/prolonger', [GestController::class, 'prolongerEmprunt'])->name('prolonger');
        Route::get('/filtre', [GestController::class, 'filterEmprunts'])->name('filtre');
    });
    
    // Gestion des stocks
    Route::prefix('stocks')->name('stocks.')->controller(StockController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/{stock}/edit', 'edit')->name('edit');
            Route::put('/{stock}', 'update')->name('update');
            Route::delete('/{stock}', 'destroy')->name('destroy');
        });

    // Gestion des ouvrages
    Route::post('/ouvrages', [EditController::class, 'store'])->name('ouvrages.creer');
    Route::get('/ouvrages/ajouter', [EditController::class, 'getCategories'])->name('ouvrages.afficher-formulaire');
    Route::delete('/ouvrages/{id}', [EditController::class, 'destroy'])->name('ouvrages.supprimer');
    Route::get('/ouvrages/{id}/modifier', [EditController::class, 'edit'])->name('ouvrages.afficher-modification');
    Route::put('/ouvrages/{id}', [EditController::class, 'update'])->name('ouvrages.mettre-a-jour');
    Route::get('/ouvrages/{ouvrage}', [EditController::class, 'apiShow'])->name('ouvrages.apiShow');
});

// --------------------- ÉDITEURS + ADMIN ---------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/ouvrages', [EditController::class, 'getLivres'])->name('admin.ouvrages');
    Route::get('/admin/ventes', [GestController::class, 'index'])->name('admin.ventes');
    Route::prefix('admin')->group(function () {
        // Routes pour la gestion des stocks
        Route::prefix('stocks')
            ->name('admin.stocks.')
            ->controller(StockController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('/{stock}/edit', 'edit')->name('edit');
                Route::put('/{stock}', 'update')->name('update');
                Route::delete('/{stock}', 'destroy')->name('destroy');
            });
    });

    // Gestion des ouvrages
    Route::post('/ouvrages', [EditController::class, 'store'])->name('ouvrages.creer');
    Route::get('/ouvrages/ajouter', [EditController::class, 'getCategories'])->name('ouvrages.afficher-formulaire');
    Route::delete('/ouvrages/{id}', [EditController::class, 'destroy'])->name('ouvrages.supprimer');
    Route::get('/ouvrages/{id}/modifier', [EditController::class, 'edit'])->name('ouvrages.afficher-modification');
    Route::put('/ouvrages/{id}', [EditController::class, 'update'])->name('ouvrages.mettre-a-jour');
    Route::get('/ouvrages/{ouvrage}', [EditController::class, 'apiShow'])->name('ouvrages.apiShow');
});

// --------------------- CATÉGORIES ---------------------
Route::middleware(['auth'])->prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [CategoriesController::class, 'index'])->name('index');
    // Route::get('/create', [CategoriesController::class, 'create'])->name('create');
    Route::post('/', [CategoriesController::class, 'store'])->name('store');
    Route::post('/{id}', [CategoriesController::class, 'update'])->name('update');
    Route::delete('/{id}', [CategoriesController::class, 'destroy'])->name('destroy');
});

// --------------------- COMMENTAIRES ---------------------
Route::middleware(['auth'])->prefix('comments')->name('comments.')->group(function () {
    Route::get('/', [CommentaireController::class, 'index'])->name('index');
    Route::get('/{commentaire}', [CommentaireController::class, 'show'])->name('show');
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


// Interface de chat IA
Route::middleware('auth')->prefix('ai')->group(function () {
    Route::get('/chat', [AIController::class, 'showChat'])->name('ai.chat');
});

// Auth routes
Auth::routes();
