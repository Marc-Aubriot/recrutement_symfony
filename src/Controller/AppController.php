<?php
// src/Controller/AppController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            
        ]);

        // ...
    }

    #[Route('/register', methods: ['GET'])]
    public function register(): Response
    {
        return $this->render('default/register.html.twig', [
            
        ]);

        // ...
    }

    #[Route('/backoffice', methods: ['GET'])]
    public function backoffice(): Response
    {
        return $this->render('default/backoffice.html.twig', [
            
        ]);

        // ...
    }
}