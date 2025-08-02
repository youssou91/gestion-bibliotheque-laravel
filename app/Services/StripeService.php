<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Crée une session de paiement Stripe
     *
     * @param float $amount Montant en euros
     * @param string $description Description du paiement
     * @param array $metadata Métadonnées supplémentaires
     * @return array
     */
    public function createCheckoutSession(float $amount, string $description, array $metadata = [])
    {
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Paiement d\'amende',
                            'description' => $description,
                        ],
                        'unit_amount' => $this->toCents($amount),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('amende.paiement.succes') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('amende.paiement.annule'),
                'metadata' => $metadata,
            ]);

            return [
                'success' => true,
                'session_id' => $session->id,
                'url' => $session->url
            ];
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de la session Stripe: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur lors de la création du paiement. Veuillez réessayer.'
            ];
        }
    }

    /**
     * Récupère les détails d'une session de paiement
     *
     * @param string $sessionId
     * @return \Stripe\Checkout\Session
     */
    public function getCheckoutSession(string $sessionId)
    {
        try {
            return Session::retrieve($sessionId);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération de la session Stripe: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Convertit un montant en euros en centimes pour Stripe
     *
     * @param float $amount
     * @return int
     */
    private function toCents(float $amount): int
    {
        return (int) round($amount * 100);
    }
}
