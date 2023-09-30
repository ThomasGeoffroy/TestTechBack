<?php

namespace App\Services;

use App\Entity\Article;

class StripeService {

    private $privateKey;

    public function __construct()
    {
        if($_ENV['APP_ENV'] === 'dev') {
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
        } else {
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_LIVE'];
        }
        
    }

    public function paymentIntent(Article $article) {
        \Stripe\Stripe::setApiKey($this->privateKey);

        return \Stripe\PaymentIntent::create([
            'amount' => 400,
            'currency' => 'eur',
            'payment_method_types' => ['card']
        ]);
    }

    public function payment(
        $amount,
        $currency,
        $description,
        array $stripeParameter
    )
    {
        \Stripe\Stripe::setApiKey($this->privateKey);
    
        $payment_intent = null;
    
        if (isset($stripeParameter['stripeIntentId'])) {
            $payment_intent = \Stripe\PaymentIntent::retrieve($stripeParameter['stripeIntentId']);
        }
    
        if ($payment_intent && $payment_intent->status === 'succeeded') {
            // Handle successful payment.
        } elseif ($payment_intent) {
            // Handle payment cancellation or failure.
            $payment_intent->cancel();
        }
    
        return $payment_intent;
    }


    public function stripe(array $stripeParameter, Article $article){

        return $this->payment(
            400,
            'eur',
            $article->getId(),
            $stripeParameter
        );
    }
}