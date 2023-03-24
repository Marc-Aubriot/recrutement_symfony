<?php
// src/Controller/BackofficeConsultantController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BackofficeConsultantController extends AbstractController
{
    #[Route('/backoffice/consultant', name:"backofficeConsultant")]
    public function backoffice(EntityManagerInterface $entityManager): Response
    {
        // securise le controlleur
        $this->denyAccessUnlessGranted('ROLE_CONSULTANT', null, "erreur 403 custom : zone restreinte aux consultants.");

        // récupères l'User Entity qui est log in, son mail et enfin l'Utilisateur Entity correspondant
        $userSecurity = $this->getUser();
        $userMail = $userSecurity->getUserIdentifier();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $userMail]);

        return $this->render('utilisateur/consultant.twig', [
            'user' => $user,
        ]);
    }
}