<?php

namespace App\Controller\Back;


use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/back", name="app_back_main")
     */
    public function index(ArticleRepository $articleRepository, UserRepository $userRepository): Response
    {

        $articles = $articleRepository->articlesMainBackOffice();
        $users = $userRepository->usersMainBackOffice();

        return $this->render('back/main.html.twig', [
            "articles" => $articles,
            "users" => $users
        ]);
    }
}
