<?php
/*
    
namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Utilisateurs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UtilisateurController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('auth');
    }
    

    
    public function updateProfile(Request $request)
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si existe
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }

            // Stocker la nouvelle photo
            $path = $request->file('photo')->store('photos', 'public');
            $user->photo = $path;
        }

        // $user->save();

        return redirect()->back()->with('success', 'Profil mis à jour !');
    }
} 
*/

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Utilisateurs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UtilisateurController extends Controller
{
    // Constantes pour les statuts
    const STATUT_ACTIF = 'actif';
    const STATUT_INACTIF = 'inactif';
    const STATUT_SUSPENDU = 'suspendu';

    /**
     * Affiche la liste des utilisateurs
     */
    public function index()
    {
        // Récupération des statistiques
        $stats = [
            'total' => Utilisateurs::count(),
            'actifs' => Utilisateurs::where('statut', self::STATUT_ACTIF)->count(),
            'admin' => Utilisateurs::where('role', Utilisateurs::ROLE_ADMIN)->count(),
            'gestionnaires' => Utilisateurs::where('role', Utilisateurs::ROLE_GESTIONNAIRE)->count(),
            'editeurs' => Utilisateurs::where('role', Utilisateurs::ROLE_EDITEUR)->count(),
            'clients' => Utilisateurs::where('role', Utilisateurs::ROLE_CLIENT)->count(),
        ];

        // Récupération des utilisateurs avec pagination
        $utilisateurs = Utilisateurs::orderBy('created_at', 'desc')->paginate(10);

        return view('gerer_utilisateurs', compact('stats', 'utilisateurs'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        return view('admin.utilisateurs.create');
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in([
                Utilisateurs::ROLE_CLIENT,
                Utilisateurs::ROLE_EDITEUR,
                Utilisateurs::ROLE_GESTIONNAIRE,
                Utilisateurs::ROLE_ADMIN
            ])],
            'statut' => ['required', Rule::in([
                self::STATUT_ACTIF,
                self::STATUT_INACTIF,
                self::STATUT_SUSPENDU
            ])],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'adresse' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
        ]);

        // Gestion de l'upload de la photo
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('profils', 'public');
        }

        // Hash du mot de passe
        $validated['password'] = Hash::make($validated['password']);

        // Création de l'utilisateur
        Utilisateurs::create($validated);

        return redirect()->route('gerer_utilisateurs')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Affiche les détails d'un utilisateur
     */
    public function show(Utilisateurs $utilisateur)
    {
        return view('admin.utilisateurs.show', compact('utilisateur'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(Utilisateurs $utilisateur)
    {
        return view('admin.utilisateurs.edit', compact('utilisateur'));
    }

    

    /**
     * Supprime un utilisateur
     */
    public function destroy(Utilisateurs $utilisateur)
    {
        // Suppression de la photo si elle existe
        if ($utilisateur->photo) {
            Storage::disk('public')->delete($utilisateur->photo);
        }

        $utilisateur->delete();

        return redirect()->route('gerer_utilisateurs')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Export des utilisateurs en CSV
     */
    public function exportCSV()
    {
        $fileName = 'utilisateurs_' . date('Y-m-d') . '.csv';
        $utilisateurs = Utilisateurs::all();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Nom', 'Prénom', 'Email', 'Rôle', 'Statut', 'Date création'];

        $callback = function () use ($utilisateurs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($utilisateurs as $utilisateur) {
                fputcsv($file, [
                    $utilisateur->id,
                    $utilisateur->nom,
                    $utilisateur->prenom,
                    $utilisateur->email,
                    $utilisateur->role,
                    $utilisateur->statut,
                    $utilisateur->created_at->format('d/m/Y')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function profileByRole()
    {
        $user = Auth::user();

        // Compter les emprunts en cours
        $empruntsCount = Emprunt::where('utilisateur_id', $user->id)
            ->where('statut', 'en_cours')
            ->count();

        // Compter les commentaires (si vous avez cette relation)
        // Compter les commentaires
        // $commentairesCount = $user->commentaires()->count();
        $donneesProfil = [
            'nom_complet' => $user->prenom . ' ' . $user->nom,
            'email' => $user->email,
            'adresse' => $user->adresse ?? 'Adresse non renseignée',
            'telephone' => $user->telephone ?? 'Téléphone non renseigné',
            'role' => $user->role,
            'statut' => $user->statut ?? 'Actif',
            'photo' => $user->photo ?? 'default-avatar.png',
            'date_inscription' => $user->created_at->format('d/m/Y'),
            'date_derniere_connexion' => optional($user->last_login_at)->format('d/m/Y H:i'),
            'emprunts_count' => $empruntsCount,
            // 'commentaires_count' => $commentairesCount,
            // Vous pouvez aussi ajouter le nombre de favoris si nécessaire
            // 'favoris_count' => $user->favoris()->count() ?? 0, // Supposant une relation favoris()
        ];

        return match ($user->role) {
            'administrateur', 'admin' => view('admin.profile', compact('donneesProfil')),
            'client'                 => view('frontOffice.profile', compact('donneesProfil')),
            'gestionnaire'           => view('gestionnaire.profile', compact('donneesProfil')),
            default                  => abort(403, 'Accès non autorisé.')
        };
    }

    // Met à jour les informations d'un utilisateur
    public function update(Request $request, $id)
    {
        $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email,' . $id,
            'role' => 'required|in:admin,editeur,gestionnaire,utilisateur',
        ]);

        $utilisateur = Utilisateurs::findOrFail($id);
        $utilisateur->prenom = $request->prenom;
        $utilisateur->nom = $request->nom;
        $utilisateur->email = $request->email;
        $utilisateur->role = $request->role;
        $utilisateur->save();

        return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    // Active ou désactive (bloque/débloque) un utilisateur
    public function toggleStatus($id)
    {
        $utilisateur = Utilisateurs::findOrFail($id);

        // Toggle statut entre 'actif' et 'inactif'
        if ($utilisateur->statut === 'actif') {
            $utilisateur->statut = 'inactif';
        } else {
            $utilisateur->statut = 'actif';
        }
        $utilisateur->save();

        return redirect()->route('admin.utilisateurs')->with('success', 'Statut utilisateur modifié avec succès.');
    }
}
