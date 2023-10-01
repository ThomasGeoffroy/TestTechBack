<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main")
     */
    public function index(Request $request ,ArticleRepository $articleRepository, PaginatorInterface $paginator, OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();


        $currentUser = $this->getUser();
        
        if($currentUser){
            $purchasedArticles = $orderRepository->findPurchasedArticlesByUser($currentUser);
            
            $articleIds = array_map(function($order) {
                return $order->getArticleId()->getId();
            }, $purchasedArticles);

            // dd($articleIds);

            $articles = $articleRepository->findAll();

            $articles = $paginator->paginate(
                $articles,
                $request->query->getInt('page', 1), 10);
    
            return $this->render('main/index.html.twig', [
                "articles" => $articles,
                "user" => $user,
                'articleIds' => $articleIds,
            ]);

        } else {

            $articles = $articleRepository->findAll();

            $articles = $paginator->paginate(
                $articles,
                $request->query->getInt('page', 1), 10);
    
            return $this->render('main/index.html.twig', [
                "articles" => $articles,
                "user" => $user,
            ]);


        }
    }
}
