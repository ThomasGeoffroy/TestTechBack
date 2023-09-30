<?php

namespace App\Manager;

use App\Entity\Article;
use App\Entity\Order;
use App\Entity\User;
use App\Services\StripeService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class ArticleManager 
{

    /**
     *
     * @var StripeService
     */
    protected $stripeService;


    /**
     *
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * 
     * @param StripeService $stripeService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(StripeService $stripeService, EntityManagerInterface $entityManager)
    {

        $this->stripeService = $stripeService;
        $this->em = $entityManager;
    }

    public function getArticles(){
        return $this->em->getRepository(Article::class)->findAll();
    }

    public function intentSecret(Article $article){

        $intent = $this->stripeService->paymentIntent($article);

        return $intent['client_secret'] ?? null;
    }

    public function stripe(array $stripeParameter, Article $article)
    {
        $data = $this->stripeService->stripe($stripeParameter, $article);
    
        $ressource = []; // Define $ressource with default values.
    
        if ($data) {
            // Check if 'stripeIntentId' is set in $stripeParameter before using it.
            if (isset($stripeParameter['stripeIntentId']) && $stripeParameter['stripeIntentId'] === 'succeeded') {
                // Handle successful payment.
            } elseif ($data['stripeIntentId'] === 'succeeded') { // Use $data to check the payment status.
                // Handle successful payment.
            } else {
                // Handle payment cancellation or failure.
                $payment_intent = \Stripe\PaymentIntent::retrieve($data['stripeIntentId']);
                $payment_intent->cancel();
            }
    
            $ressource = [
                'stripeBrand' => $data['charges']['data'][0]['payment_method_details']['card']['brand'],
                'stripeLast4' => $data['charges']['data'][0]['payment_method_details']['card']['last4'],
                'stripeId' => $data['charges']['data'][0]['id'],
                'stripeStatus' => $data['charges']['data'][0]['id']['status'],
                'stripeToken' => $data['client_secret'],
            ];
        }
    
        return $ressource;
    }

    public function create_subscription(array $ressource, Article $article, User $user)
    {
        $order = new Order();
        $order->setUserId($user);
        $order->setArticleId($article);
        $order->setPrice($article->getPrice());
    
        // Check if 'stripeBrand' key exists in $ressource before accessing it.
        if (isset($ressource['stripeBrand'])) {
            $order->setBrandStripe($ressource['stripeBrand']);
        }
        
        // Check if 'stripeLast4' key exists in $ressource before accessing it.
        if (isset($ressource['stripeLast4'])) {
            $order->setLast4Stripe($ressource['stripeLast4']);
        }
        
        // Check if 'stripeId' key exists in $ressource before accessing it.
        if (isset($ressource['stripeId'])) {
            $order->setIdChargeStripe($ressource['stripeId']);
        }
        
        // Check if 'stripeToken' key exists in $ressource before accessing it.
        if (isset($ressource['stripeToken'])) {
            $order->setStripeToken($ressource['stripeToken']);
        }
        
        // Check if 'stripeStatus' key exists in $ressource before accessing it.
        if (isset($ressource['stripeStatus'])) {
            $order->setStatusStripe($ressource['stripeStatus']);
        }
    
        $order->setUpdatedAt(new DateTimeImmutable());
        $order->setCreatedAt(new DateTimeImmutable());
    
        $this->em->persist($order);
        $this->em->flush();
    }

}