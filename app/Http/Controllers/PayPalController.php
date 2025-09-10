<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Amende;
use Illuminate\Support\Str;

class PayPalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['handleReturn', 'handleWebhook']);
    }
    public function createPayment(Request $request)
    {
        try {
            // Journaliser les données reçues pour le débogage
            $requestData = $request->all();
            \Log::info('Requête de paiement reçue', [
                'donnees' => $requestData,
                'en-tetes' => $request->headers->all(),
                'ip' => $request->ip(),
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type')
            ]);
            
            // Afficher les données brutes de la requête pour le débogage
            \Log::info('Données brutes de la requête: ' . file_get_contents('php://input'));
            
            // Vérifier que les champs requis sont présents
            $hasAmendeId = $request->has('amende_id');
            $hasAmount = $request->has('amount');
            
            \Log::info('Champs présents dans la requête', [
                'amende_id' => $hasAmendeId ? 'présent' : 'manquant',
                'amount' => $hasAmount ? 'présent' : 'manquant',
                'tous_les_champs' => $request->has(['amende_id', 'amount']) ? 'tous présents' : 'certains manquants'
            ]);
            
            if (!$hasAmendeId || !$hasAmount) {
                throw new \Exception(sprintf(
                    'Champs manquants dans la requête. amende_id: %s, amount: %s',
                    $hasAmendeId ? 'présent' : 'manquant',
                    $hasAmount ? 'présent' : 'manquant'
                ));
            }

            // Valider les données de la requête
            $validated = $request->validate([
                'amende_id' => 'required|integer|exists:amendes,id',
                'amount' => 'required|numeric|min:0.01'
            ], [
                'amende_id.required' => 'L\'identifiant de l\'amende est requis.',
                'amende_id.integer' => 'L\'identifiant de l\'amende doit être un nombre entier.',
                'amende_id.exists' => 'L\'amende spécifiée n\'existe pas.',
                'amount.required' => 'Le montant est requis.',
                'amount.numeric' => 'Le montant doit être un nombre.',
                'amount.min' => 'Le montant doit être supérieur à 0.'
            ]);

            \Log::info('Validation réussie', ['donnees_validees' => $validated]);

            // Récupérer l'amende
            $amende = Amende::find($validated['amende_id']);
            
            if (!$amende) {
                \Log::error('Amende non trouvée', ['amende_id' => $validated['amende_id']]);
                return response()->json([
                    'success' => false,
                    'message' => 'L\'amende spécifiée n\'existe pas.'
                ], 404);
            }
            
            // Vérifier que le montant correspond
            $montantAttendu = (float) $amende->montant;
            $montantRecu = (float) $validated['amount'];
            
            if (abs($montantAttendu - $montantRecu) > 0.01) {
                \Log::error('Montant incorrect', [
                    'attendu' => $montantAttendu, 
                    'recu' => $montantRecu,
                    'amende' => $amende->toArray()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => sprintf('Le montant (%.2f €) ne correspond pas à l\'amende (%.2f €).', 
                        $montantRecu, $montantAttendu)
                ], 400);
            }
            
            // Vérifier que l'email commercial est configuré
            $businessEmail = config('services.paypal.business_email');
            if (empty($businessEmail)) {
                $errorMsg = 'Configuration PayPal incomplète: PAYPAL_BUSINESS_EMAIL manquant';
                \Log::error($errorMsg);
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de configuration du paiement. Veuillez contacter l\'administrateur.'
                ], 500);
            }
            
            // Générer un identifiant unique pour cette transaction
            $transactionId = 'PAYPAL-' . Str::random(16);
            
            // Enregistrer l'ID de transaction dans l'amende
            $amende->transaction_id = $transactionId;
            $amende->save();
            
            // URL de retour après paiement
            $returnUrl = route('frontOffice.paypal.return');
            $cancelUrl = route('frontOffice.profile') . '?cancel_payment=true';
            
            // Créer l'URL de paiement PayPal
            $isSandbox = config('services.paypal.mode') === 'sandbox';
            $paypalUrl = $isSandbox 
                ? 'https://www.sandbox.paypal.com/cgi-bin/webscr'
                : 'https://www.paypal.com/cgi-bin/webscr';
                
            $params = [
                'cmd' => '_xclick',
                'business' => $businessEmail,
                'item_name' => 'Paiement amende #' . $amende->id,
                'amount' => number_format($amende->montant, 2, '.', ''),
                'currency_code' => 'EUR',
                'return' => $returnUrl . '?success=true&tx=' . $transactionId,
                'cancel_return' => $cancelUrl,
                'notify_url' => route('frontOffice.paypal.webhook'),
                'custom' => json_encode([
                    'amende_id' => $amende->id,
                    'user_id' => auth()->id(),
                    'transaction_id' => $transactionId
                ]),
                'no_shipping' => '1',
                'no_note' => '1',
                'rm' => '2' // Retourne les données par POST (2) plutôt que par GET (1)
            ];
            
            // Construire l'URL de redirection complète
            $redirectUrl = $paypalUrl . '?' . http_build_query($params);
            
            // Retourner une réponse JSON avec l'URL de redirection
            return response()->json([
                'success' => true,
                'redirect_url' => $redirectUrl
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la préparation du paiement: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function handleReturn(Request $request)
    {
        try {
            // Journaliser la requête de retour
            \Log::info('Requête de retour PayPal reçue', [
                'query' => $request->query(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            if ($request->query('success') === 'true' && $request->has('tx')) {
                $transactionId = $request->query('tx');
                
                // Vérifier le statut du paiement avec PayPal
                $verifyUrl = config('services.paypal.mode') === 'sandbox'
                    ? 'https://www.sandbox.paypal.com/cgi-bin/webscr'
                    : 'https://www.paypal.com/cgi-bin/webscr';
                
                $pdtToken = config('services.paypal.pdt_token');
                if (empty($pdtToken)) {
                    throw new \Exception('PDT Token non configuré');
                }
                
                $response = Http::asForm()->post($verifyUrl, [
                    'cmd' => '_notify-synch',
                    'tx' => $transactionId,
                    'at' => $pdtToken,
                ]);
                
                // Journaliser la réponse de PayPal
                \Log::info('Réponse de la vérification PayPal', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                if ($response->successful() && str_starts_with($response->body(), 'SUCCESS')) {
                    // Mettre à jour le statut du paiement
                    $amende = Amende::where('transaction_id', $transactionId)->first();
                    
                    if ($amende) {
                        $amende->update([
                            'statut' => 'payée',
                            'date_paiement' => now()
                        ]);
                        
                        \Log::info('Paiement confirmé avec succès', [
                            'amende_id' => $amende->id,
                            'transaction_id' => $transactionId
                        ]);
                        
                        return redirect()->route('frontOffice.profile')
                            ->with('success', 'Paiement effectué avec succès !');
                    } else {
                        \Log::error('Amende non trouvée pour la transaction', [
                            'transaction_id' => $transactionId
                        ]);
                        return redirect()->route('frontOffice.profile')
                            ->with('error', 'Transaction introuvable dans notre système.');
                    }
                } else {
                    $errorMessage = 'Échec de la vérification du paiement avec PayPal';
                    if (!$response->successful()) {
                        $errorMessage .= ' - Erreur HTTP: ' . $response->status();
                    }
                    \Log::error($errorMessage, [
                        'transaction_id' => $transactionId,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    
                    return redirect()->route('frontOffice.profile')
                        ->with('error', $errorMessage);
                }
            } else {
                $errorMessage = 'Paramètres de retour PayPal invalides';
                if (!$request->has('tx')) {
                    $errorMessage .= ' - Transaction ID manquant';
                }
                \Log::warning($errorMessage, [
                    'query' => $request->query()
                ]);
                
                return redirect()->route('frontOffice.profile')
                    ->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            \Log::error('Erreur lors du traitement du retour PayPal: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('frontOffice.profile')
                ->with('error', 'Une erreur est survenue lors du traitement de votre paiement: ' . $e->getMessage());
        }
    }
    
    public function handleWebhook(Request $request)
    {
        // Journaliser la requête webhook reçue
        $ipnData = $request->all();
        $ipnTransactionId = $request->input('txn_id');
        
        \Log::info('Webhook PayPal reçu', [
            'transaction_id' => $ipnTransactionId,
            'payment_status' => $request->input('payment_status'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'data' => $ipnData
        ]);
        
        try {
            // Vérifier la validité de la notification
            $verifyUrl = config('services.paypal.mode') === 'sandbox'
                ? 'https://www.sandbox.paypal.com/cgi-bin/webscr'
                : 'https://www.paypal.com/cgi-bin/webscr';
            
            // Préparer les données pour la vérification
            $postData = ['cmd' => '_notify-validate'];
            $postData += $ipnData; // Ajouter toutes les données IPN
            
            // Envoyer la requête de vérification à PayPal
            $response = Http::withHeaders([
                'User-Agent' => 'PHP-IPN-Verification-Script',
                'Connection' => 'Close',
            ])->asForm()->post($verifyUrl, $postData);
            
            $verificationResponse = $response->body();
            $isVerified = $response->successful() && $verificationResponse === 'VERIFIED';
            
            // Journaliser la réponse de vérification
            \Log::info('Réponse de vérification du webhook PayPal', [
                'transaction_id' => $ipnTransactionId,
                'status' => $response->status(),
                'response' => $verificationResponse,
                'is_verified' => $isVerified
            ]);
            
            if ($isVerified) {
                // Traiter la notification IPN vérifiée
                $paymentStatus = $request->input('payment_status');
                $customData = $request->input('custom');
                
                // Journaliser le statut du paiement
                \Log::info('Statut du paiement reçu dans le webhook', [
                    'transaction_id' => $ipnTransactionId,
                    'payment_status' => $paymentStatus,
                    'custom_data' => $customData
                ]);
                
                // Vérifier si le paiement est complété
                if ($paymentStatus === 'Completed') {
                    $custom = json_decode($customData, true);
                    
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new \Exception('Erreur de décodage des données personnalisées: ' . json_last_error_msg());
                    }
                    
                    if (empty($custom['amende_id'])) {
                        throw new \Exception('ID d\'amende manquant dans les données personnalisées');
                    }
                    
                    // Trouver et mettre à jour l'amende
                    $amende = Amende::find($custom['amende_id']);
                    
                    if (!$amende) {
                        throw new \Exception('Amende non trouvée: ' . $custom['amende_id']);
                    }
                    
                    // Vérifier si le paiement n'a pas déjà été traité
                    if ($amende->statut !== 'payée') {
                        $amende->update([
                            'statut' => 'payée',
                            'date_paiement' => now(),
                            'transaction_id' => $ipnTransactionId
                        ]);
                        
                        \Log::info('Paiement confirmé via webhook', [
                            'amende_id' => $amende->id,
                            'transaction_id' => $ipnTransactionId,
                            'montant' => $request->input('mc_gross'),
                            'devise' => $request->input('mc_currency')
                        ]);
                    } else {
                        \Log::info('Paiement déjà traité pour cette amende', [
                            'amende_id' => $amende->id,
                            'transaction_id' => $ipnTransactionId
                        ]);
                    }
                } else {
                    // Journaliser les statuts de paiement non gérés
                    \Log::info('Statut de paiement non géré', [
                        'transaction_id' => $ipnTransactionId,
                        'payment_status' => $paymentStatus
                    ]);
                }
                
                return response()->json(['status' => 'success', 'message' => 'Webhook traité avec succès']);
            } else {
                // Journaliser l'échec de la vérification
                $errorMessage = 'Échec de la vérification du webhook PayPal';
                if (!$response->successful()) {
                    $errorMessage .= ' - Erreur HTTP: ' . $response->status();
                }
                
                \Log::error($errorMessage, [
                    'transaction_id' => $ipnTransactionId,
                    'status' => $response->status(),
                    'response' => $verificationResponse,
                    'request_data' => $ipnData
                ]);
                
                return response()->json([
                    'status' => 'error',
                    'message' => $errorMessage
                ], 400);
            }
        } catch (\Exception $e) {
            // Journaliser les erreurs non gérées
            \Log::error('Erreur lors du traitement du webhook PayPal: ' . $e->getMessage(), [
                'transaction_id' => $ipnTransactionId,
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'request_data' => $ipnData
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors du traitement du webhook: ' . $e->getMessage()
            ], 500);
        }
    }
}
