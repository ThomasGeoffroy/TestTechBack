<?php

namespace App\Services;

use App\Entity\Article;

class StripeService {

    private $privateKey;

    public function __construct()
    {
        $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
    }

    public function paymentIntent(Article $article) {
        \Stripe\Stripe::setApiKey($privateKey);

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
        array $stipeParameter
    )
    {
        \Stripe\Stripe::setApiKey($privateKey);
        $payment_intent =null;

        if(isset($stipeParameter['stripeIntentId'])){
            $payment_intent = \Stripe\PaymentIntent::retrieve($stipeParameter['stripeIntentId']);
        }
            if($stipeParameter['stripeIntentId'] === 'succeeded'){

            }else{
                $payment_intent->cancel();
            }

        return $payment_intent;
    }


    public function stripe(array $stipeParameter, Article $article){

        return $this->payment(
            400,
            'eur',
            $article->getId(),
            $stipeParameter
        );
    }
}