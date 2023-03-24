<?php
// src/Controller/BackofficeAdminController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BackofficeAdminController extends AbstractController
{
    #[Route('/backoffice/admin', name:"backofficeAdmin")]
    public function backoffice(EntityManagerInterface $entityManager, Request $request): Response
    {
        // securise le controlleur
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "erreur 403 custom : zone restreinte aux administrateurs.");

        // récupères l'User Entity qui est log in, son mail et enfin l'Utilisateur Entity correspondant
        $userSecurity = $this->getUser();
        $userMail = $userSecurity->getUserIdentifier();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $userMail]);

        // set les variables
        $error_message = null;
        $validation_message = null;

        return $this->render('utilisateur/administrateur.twig', [
            'user' => $user,
            'errorMessage' => $error_message,
            'validationMessage' => $validation_message,
        ]);
    }
}