<?php

namespace App\Controller;


use App\Repository\ArticleRepository;
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
    public function index(Request $request ,ArticleRepository $articleRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();


        $articles = $articleRepository->findAll();

        $articles = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1), 10);


        return $this->render('main/index.html.twig', [
            "articles" => $articles,
            "user" => $user
        ]);
    }
}
