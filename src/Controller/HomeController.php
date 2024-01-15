<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_decision_index');
        }
        return $this->render('home/index.html.twig');
    }

    #[Route('/variables', name: 'variables')]
    public function test(): Response
    {
        return $this->render('variables.html.twig');
    }
}
