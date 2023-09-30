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
    public function show(Article $article, User $user, OrderRepository $orderRepository): Response
    {
        // Get the current user
        $currentUser = $this->getUser();

        // Check if the current user has paid for the article
        $hasPaid = $orderRepository->findOneBy(['userId' => $currentUser, 'articleId' => $article]);

        dd($hasPaid);

        // Set 'hasPaid' as a template variable
        return $this->render('front/articleshow.html.twig', [
            'article' => $article,
            'hasPaid' => $hasPaid,
        ]);
    }

}
