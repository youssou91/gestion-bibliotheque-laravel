<?php

namespace App\Http\Controllers;

use App\Models\Amende;
use App\Models\Emprunt;
use App\Models\Reservation;
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
            'role' => ['required', Rule::in([
                Utilisateurs::ROLE_CLIENT,
                Utilisateurs::ROLE_EDITEUR,
                Utilisateurs::ROLE_GESTIONNAIRE,
                Utilisateurs::ROLE_ADMIN
            ])],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'adresse' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
        ]);

        // Générer automatiquement le mot de passe : prénom + 1234
        $passwordAuto = $request->prenom . '1234';
        $validated['password'] = Hash::make($passwordAuto);

        // Statut par défaut : actif
        $validated['statut'] = 'actif';

        // Traiter la photo comme dans l'extrait fourni
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_nom = time() . $photo->getClientOriginalName();
            $photo->move(public_path('assets/img'), $photo_nom);
            $validated['photo'] = $photo_nom;
        }

        // Création de l'utilisateur
        Utilisateurs::create($validated);

        return redirect()->view('gerer_utilisateurs') // utilise route() plutôt que view()
            ->with('success', 'Utilisateur créé avec succès. Mot de passe par défaut : ' . $passwordAuto);
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

        // Compter les emprunts en cours et le total
        $empruntsCount = Emprunt::where('utilisateur_id', $user->id)
            ->where('statut', 'en_cours')
            ->count();

        $totalEmprunts = Emprunt::where('utilisateur_id', $user->id)->count();

        // Compter les reservations en attente et le total
        $reservationsCount = Reservation::where('utilisateur_id', $user->id)
            ->where('statut', 'en_attente')
            ->count();

        $totalReservations = Reservation::where('utilisateur_id', $user->id)->count();

        // Compter les amendes impayées et le total (avec le nouveau enum)
        $amendesCount = Amende::where('utilisateur_id', $user->id)
            ->where('statut', 'impayée')
            ->count();

        $totalAmendes = Amende::where('utilisateur_id', $user->id)->count();

        // Récupérer toutes les amendes pour affichage dans le modal
        $amendes = Amende::with(['emprunt.ouvrage'])
            ->where('utilisateur_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $donneesProfil = [
            'id' => $user->id,
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
            'total_emprunts' => $totalEmprunts,
            'reservations_count' => $reservationsCount,
            'total_reservations' => $totalReservations,
            'amendes_count' => $amendesCount,
            'total_amendes' => $totalAmendes,
        ];

        return match ($user->role) {
            'administrateur', 'admin' => view('admin.profile', compact('donneesProfil')),
            'client' => view('frontOffice.profile', compact('donneesProfil', 'amendes')),
            'gestionnaire' => view('gestionnaire.profile', compact('donneesProfil')),
            default => abort(403, 'Accès non autorisé.')
        };
    }

    public function payerAmende(Request $request)
    {
        $request->validate([
            'amende_id' => 'required',
            'methode_paiement' => 'required|in:carte,especes,cheque',
            'numero_carte' => 'required_if:methode_paiement,carte',
            'expiration_carte' => 'required_if:methode_paiement,carte',
            'cvv_carte' => 'required_if:methode_paiement,carte'
        ]);

        $user = Auth::user();

        if ($request->amende_id === 'all') {
            // Payer toutes les amendes impayées
            $count = Amende::where('utilisateur_id', $user->id)
                ->where('statut', 'impayée')
                ->update([
                    'statut' => 'payée',
                    'date_paiement' => now(),
                    'methode_paiement' => $request->methode_paiement
                ]);

            return response()->json([
                'success' => true,
                'message' => $count > 0
                    ? "Toutes vos amendes ont été payées avec succès."
                    : "Aucune amende à payer."
            ]);
        } else {
            // Payer une amende spécifique
            $amende = Amende::where('utilisateur_id', $user->id)
                ->where('id', $request->amende_id)
                ->firstOrFail();

            $amende->update([
                'statut' => 'payée',
                'date_paiement' => now(),
                'methode_paiement' => $request->methode_paiement
            ]);

            return response()->json([
                'success' => true,
                'message' => 'L\'amende a été payée avec succès.'
            ]);
        }
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

    // Met à jour le profil de l'utilisateur
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $utilisateur = Auth::user();

        // Traiter la photo
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_nom = time() . '_' . $photo->getClientOriginalName();

            // Supprimer l'ancienne image si elle existe et n'est pas un avatar par défaut
            if (!empty($utilisateur->photo)) {
                $img_path = public_path('assets/img/' . $utilisateur->photo);
                if (file_exists($img_path)) {
                    @unlink($img_path); // le @ évite d'afficher un warning si jamais un problème survient
                }
            }

            // Enregistrer la nouvelle image
            $photo->move(public_path("assets/img"), $photo_nom);
            $utilisateur->photo = $photo_nom;

            $utilisateur->save();

            return redirect()->back()->with('success', 'Avatar mis à jour avec succès !');
        }

        // Si aucune photo n'a été envoyée
        return redirect()->back()->with('warning', 'Aucune image sélectionnée.');
    }


    // Met à jour le mot de passe de l'utilisateur
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Le mot de passe actuel est incorrect.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Mot de passe mis à jour avec succès.');
    }
}
