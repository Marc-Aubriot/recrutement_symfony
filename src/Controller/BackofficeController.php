<?php
// src/Controller/BackofficeController.php
namespace App\Controller;

use App\Form\RegisterFormType;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BackofficeController extends AbstractController
{
    #[Route('/backoffice', name:"backoffice")]
    public function backoffice(): Response
    {
        return $this->render('default/backoffice.html.twig', [
            
        ]);
    }
}