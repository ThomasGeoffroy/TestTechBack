<?php

namespace App\Controller;

use App\Entity\Article;
use App\Manager\ArticleManager;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/payment/article/{id}", name="payment", methods={"GET","POST"})
     */
    public function payment(Article $article, ArticleManager $articleManager): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('payments/checkout.html.twig', [
            'user' => $this->getUser(),
            'intentSecret' => $articleManager->intentSecret($article),
            'article' =>$article,
        ]);
    }


    /**
     * @Route("/user/subscription/{id}/payment/load", name="subscription_payment", methods={"GET","POST"})
     */
    public function subscription(Article $article, Request $request, ArticleManager $articleManager)
    {

        $user = $this->getUser();

        if ($request->getMethod() === "POST" ) {
            $ressource = $articleManager->stripe($_POST, $article);

            if(null !== $ressource) {
                $articleManager->create_subscription($ressource, $article, $user);

                return $this->render('dashboard/reponse.html.twig', [
                    'article' =>$article
                ]);
            }
        }


        return $this->redirectToRoute('payment', ['id' => $article->getId()]);
    }






}
