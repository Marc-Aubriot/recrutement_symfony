<?php
// src/Controller/AttenteValidationController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AttenteValidationController extends AbstractController
{
    #[Route('/awaitvalidation', name:"attentevalidation")]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // récupères l'User Entity qui est log in
        $userSecurity = $this->getUser();

        // récupères l'Utilisateur Entity correspondant à l'adresse mail de User Security
        $userMail = $userSecurity->getUserIdentifier();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $userMail]);

        // premier render de la page
        return $this->render('default/components/attentevalidation.twig', [
            'user' => $user,
            'errorMessage' => $error_message = null,
        ]);
    }
}