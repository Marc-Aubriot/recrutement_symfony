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

    #[Route('/back', methods: ['GET'])]
    public function test(): Response
    {
        return $this->render('default/index.html.twig', [
            
        ]);

        // ...
    }
}