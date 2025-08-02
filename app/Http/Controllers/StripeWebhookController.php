<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use App\Models\Amende;

class StripeWebhookController extends Controller
{
    /**
     * Gère les événements webhook Stripe
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $event = null;

        try {
            // Vérifier la signature du webhook
            $event = Webhook::constructEvent(
                $payload, 
                $sigHeader, 
                config('services.stripe.webhook_secret')
            );
        } catch (\UnexpectedValueException $e) {
            // Données webhook invalides
            Log::error('Webhook Stripe invalide : ' . $e->getMessage());
            return response()->json(['error' => 'Données webhook invalides'], 400);
        } catch (SignatureVerificationException $e) {
            // Signature invalide
            Log::error('Signature webhook Stripe invalide : ' . $e->getMessage());
            return response()->json(['error' => 'Signature webhook invalide'], 400);
        }

        // Gérer l'événement
        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;
            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
                break;
            // Ajoutez d'autres événements si nécessaire
            default:
                Log::info('Événement Stripe non géré : ' . $event->type);
        }

        return response()->json(['received' => true]);
    }

    /**
     * Gère l'événement checkout.session.completed
     *
     * @param  \Stripe\Checkout\Session  $session
     * @return void
     */
    protected function handleCheckoutSessionCompleted($session)
    {
        // Cette méthode est appelée lorsque le client termine le processus de paiement
        // Le statut du paiement n'est pas encore confirmé à ce stade
        Log::info('Session de paiement complétée : ' . $session->id);
        
        // Vous pouvez ajouter une logique supplémentaire ici si nécessaire
    }

    /**
     * Gère l'événement payment_intent.succeeded
     *
     * @param  \Stripe\PaymentIntent  $paymentIntent
     * @return void
     */
    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        Log::info('Paiement réussi : ' . $paymentIntent->id);
        
        // Trouver l'amende liée à ce paiement
        $amende = Amende::where('transaction_id', $paymentIntent->id)->first();
        
        if ($amende && $amende->statut !== 'payee') {
            $amende->update([
                'statut' => 'payee',
                'date_paiement' => now(),
                'moyen_paiement' => 'stripe',
                'transaction_id' => $paymentIntent->id
            ]);
            
            Log::info("Amende #{$amende->id} marquée comme payée via webhook");
        }
    }

    /**
     * Gère l'événement payment_intent.payment_failed
     *
     * @param  \Stripe\PaymentIntent  $paymentIntent
     * @return void
     */
    protected function handlePaymentIntentFailed($paymentIntent)
    {
        Log::warning('Échec du paiement : ' . $paymentIntent->id);
        
        // Vous pouvez ajouter une logique pour gérer les échecs de paiement
        // Par exemple, envoyer un email à l'utilisateur
    }
}
