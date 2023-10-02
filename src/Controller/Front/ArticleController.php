<?php

namespace App\Controller\Front;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/{id}", name="app_article_show", methods={"GET"})
     */
    public function show(Article $article, OrderRepository $orderRepository): Response
    {
        // Get the current user
        $currentUser = $this->getUser();

        // dd($currentUser);

        if ($currentUser) {
            $purchasedArticles = $orderRepository->findPurchasedArticlesByUser($currentUser);

            $articleIds = array_map(function ($order) {
                return $order->getArticle()->getId();
            }, $purchasedArticles);
        }

        if (empty ($articleIds)) {

            return $this->render('front/articleshow.html.twig', [
                'article' => $article,
            ]);
            # code...
        } else {

            return $this->render('front/articleshow.html.twig', [
                'article' => $article,
                'articleIds' => $articleIds
            ]);
        }

    }
}