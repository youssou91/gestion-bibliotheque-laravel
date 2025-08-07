<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Amende;
use App\Models\Utilisateurs;
use App\Models\Ouvrages;
use App\Models\Emprunt;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AmendeController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }
    
    /**
     * Obtient un jeton d'accès pour l'API PayPal
     * 
     * @return string
     * @throws \Exception
     */
    protected function getPaypalAccessToken()
    {
        try {
            $client = new \GuzzleHttp\Client();
            
            $response = $client->post(
                config('services.paypal.base_url') . '/v1/oauth2/token',
                [
                    'auth' => [
                        config('services.paypal.client_id'),
                        config('services.paypal.secret')
                    ],
                    'form_params' => [
                        'grant_type' => 'client_credentials'
                    ]
                ]
            );
            
            $data = json_decode($response->getBody(), true);
            
            if (!isset($data['access_token'])) {
                throw new \Exception('Impossible d\'obtenir le jeton d\'accès PayPal');
            }
            
            return $data['access_token'];
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du token PayPal: ' . $e->getMessage());
            throw new \Exception('Erreur d\'authentification avec PayPal. Veuillez réessayer plus tard.');
        }
    }
    /**
     * Traite un paiement PayPal pour une amende
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function payerAmendePaypal(Request $request, $amendeId)
    {
        try {
            $request->validate([
                'orderID' => 'required|string',
                'payerID' => 'required|string',
                'paymentID' => 'required|string',
                'paymentStatus' => 'required|string',
                'payerEmail' => 'required|email',
                'payerName' => 'required|string',
                'amount' => 'required|numeric|min:0.01'
            ]);

            // Récupérer l'amende
            $amende = Amende::findOrFail($amendeId);
            
            // Vérifier si l'amende est déjà payée
            if ($amende->statut === 'payée') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette amende a déjà été payée.'
                ], 400);
            }

            // Vérifier le montant
            if (bccomp($amende->montant, $request->amount, 2) !== 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le montant payé ne correspond pas au montant de l\'amende.'
                ], 400);
            }

            // Vérifier la transaction PayPal via l'API
            $client = new \GuzzleHttp\Client();
            $accessToken = $this->getPaypalAccessToken();

            $response = $client->get(
                config('services.paypal.base_url') . "/v2/checkout/orders/" . $request->orderID,
                [
                    'headers' => [
                        'Authorization' => "Bearer {$accessToken}",
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'Accept-Language' => 'fr_FR'
                    ]
                ]
            );

            $orderData = json_decode($response->getBody(), true);

            // Valider la réponse de PayPal
            if (!isset($orderData['status']) || $orderData['status'] !== 'COMPLETED') {
                return response()->json([
                    'success' => false,
                    'message' => 'Le paiement n\'a pas été validé par PayPal.'
                ], 400);
            }

            // Vérifier le montant dans la réponse de PayPal
            $paypalAmount = $orderData['purchase_units'][0]['amount']['value'];
            if (bccomp($amende->montant, $paypalAmount, 2) !== 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le montant reçu de PayPal ne correspond pas au montant attendu.'
                ], 400);
            }

            // Mettre à jour l'amende
            $amende->update([
                'statut' => 'payée',
                'date_paiement' => now(),
                'mode_paiement' => 'paypal',
                'reference_paiement' => $request->paymentID,
                'donnees_paiement' => json_encode([
                    'order_id' => $request->orderID,
                    'payer_id' => $request->payerID,
                    'payer_email' => $request->payerEmail,
                    'payer_name' => $request->payerName,
                    'amount' => $request->amount,
                    'currency' => $orderData['purchase_units'][0]['amount']['currency_code'] ?? 'EUR',
                    'paypal_response' => $orderData
                ])
            ]);

            // Envoyer un email de confirmation (à implémenter)
            // $this->sendPaymentConfirmation($amende);

            return response()->json([
                'success' => true,
                'message' => 'Paiement validé avec succès.',
                'payment_id' => $request->paymentID,
                'redirect_url' => route('profile') . '?payment=success&amende=' . $amende->id
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Amende introuvable.'
            ], 404);
            
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $error = json_decode($response->getBody()->getContents(), true);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification du paiement PayPal: ' . 
                            ($error['message'] ?? 'Erreur inconnue')
            ], 400);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du paiement PayPal: ' . $e->getMessage(), [
                'amende_id' => $amendeId ?? null,
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du traitement du paiement.'
            ], 500);
        }
    }

    public function index(Request $request)
    {
        // Requête de base avec les relations
        $query = Amende::with(['utilisateur', 'ouvrage', 'emprunt'])
                    ->latest();
        
        // Filtres possibles
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('utilisateur', function($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%");
            })->orWhereHas('ouvrage', function($q) use ($search) {
                $q->where('titre', 'like', "%$search%");
            });
        }

        // Pagination avec 20 éléments par page
        $amendes = $query->paginate(20)
                    ->appends($request->query());

        // Calcul des statistiques (optimisé)
        $stats = [
            'total' => Amende::count(),
            'impayees' => Amende::where('statut', 'impayée')->count(),
            'payees' => Amende::where('statut', 'payée')->count(),
            'montant_total' => Amende::sum('montant'),
            'montant_impaye' => Amende::where('statut', 'impayée')->sum('montant'),
            'montant_paye' => Amende::where('statut', 'payée')->sum('montant')
        ];

        return view('amendes', compact('amendes', 'stats'));
    }

    public function create()
    {
        $utilisateurs = Utilisateurs::all();
        $ouvrages = Ouvrages::all();
        $emprunts = Emprunt::all();

        return view('amendes', compact('utilisateurs', 'ouvrages', 'emprunts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'ouvrage_id' => 'required|exists:ouvrages,id',
            'emprunt_id' => 'nullable|exists:emprunts,id',
            'montant' => 'required|numeric|min:0',
            'motif' => 'nullable|string',
        ]);

        Amende::create([
            'utilisateur_id' => $request->utilisateur_id,
            'ouvrage_id' => $request->ouvrage_id,
            'emprunt_id' => $request->emprunt_id,
            'montant' => $request->montant,
            'motif' => $request->motif,
            'statut' => 'impayee'
        ]);

        return redirect()->route('admin.amendes')->with('success', 'Amende ajoutée avec succès.');
    }

    public function edit(Amende $amende)
    {
        $utilisateurs = Utilisateurs::all();
        $ouvrages = Ouvrages::all();
        $emprunts = Emprunt::all();

        return view('admin.amendes', compact('amende', 'utilisateurs', 'ouvrages', 'emprunts'));
    }

    public function update(Request $request, Amende $amende)
    {
        $request->validate([
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'ouvrage_id' => 'required|exists:ouvrages,id',
            'emprunt_id' => 'nullable|exists:emprunts,id',
            'montant' => 'required|numeric|min:0',
            'motif' => 'nullable|string',
            // 'est_payee' => 'required|boolean',
        ]);

        $amende->update($request->all());

        return redirect()->route('admin.amendes')->with('success', 'Amende mise à jour.');
    }

    public function destroy(Amende $amende)
    {
        $amende->delete();
        return redirect()->route('admin.amendes')->with('success', 'Amende supprimée.');
    }

    /**
     * Initialise un paiement Stripe pour une amende
     */
    public function initierPaiementStripe(Amende $amende)
    {
        try {
            if ($amende->statut === 'payee') {
                return redirect()->back()->with('error', 'Cette amende a déjà été payée.');
            }

            $description = "Paiement de l'amende #{$amende->id} pour " . $amende->ouvrage->titre;
            
            $result = $this->stripeService->createCheckoutSession(
                $amende->montant,
                $description,
                [
                    'amende_id' => $amende->id,
                    'utilisateur_id' => $amende->utilisateur_id,
                    'ouvrage_id' => $amende->ouvrage_id
                ]
            );

            if ($result['success']) {
                return redirect($result['url']);
            }

            return redirect()->back()->with('error', $result['message'] ?? 'Erreur lors de l\'initialisation du paiement.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'initialisation du paiement Stripe: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'initialisation du paiement.');
        }
    }

    /**
     * Traite le retour réussi de Stripe
     */
    public function succesPaiementStripe(Request $request)
    {
        try {
            $sessionId = $request->query('session_id');
            
            if (!$sessionId) {
                return redirect()->route('amendes.index')->with('error', 'Session de paiement invalide.');
            }

            $session = $this->stripeService->getCheckoutSession($sessionId);
            
            if (!$session || $session->payment_status !== 'paid') {
                return redirect()->route('amendes.index')->with('error', 'Paiement non validé.');
            }

            // Récupérer l'ID de l'amende depuis les métadonnées
            $amendeId = $session->metadata->amende_id ?? null;
            
            if ($amendeId) {
                $amende = Amende::findOrFail($amendeId);
                $amende->update([
                    'statut' => 'payee',
                    'date_paiement' => now(),
                    'moyen_paiement' => 'stripe',
                    'transaction_id' => $session->payment_intent
                ]);

                return redirect()->route('amendes.index')
                    ->with('success', 'Paiement effectué avec succès !');
            }

            return redirect()->route('amendes.index')
                ->with('success', 'Paiement effectué avec succès !');

        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du paiement réussi: ' . $e->getMessage());
            return redirect()->route('amendes.index')
                ->with('error', 'Une erreur est survenue lors du traitement de votre paiement.');
        }
    }

    /**
     * Gère l'annulation du paiement
     */
    public function echecPaiementStripe()
    {
        return redirect()->route('amendes.index')
            ->with('warning', 'Le paiement a été annulé. Vous pouvez réessayer si nécessaire.');
    }
}
