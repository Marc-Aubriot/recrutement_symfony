<?php
// src/Controller/BackofficeCandidatController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackofficeCandidatController extends AbstractController
{
    #[Route('/backoffice/candidat', name:"backofficeCandidat")]
    public function backoffice(EntityManagerInterface $entityManager): Response
    {
        // securise le controlleur
        $this->denyAccessUnlessGranted('ROLE_CANDIDAT', null, "erreur 403 custom : zone restreinte aux candidats.");

        // récupères l'User Entity qui est log in, son mail et enfin l'Utilisateur Entity correspondant
        $userSecurity = $this->getUser();
        $userMail = $userSecurity->getUserIdentifier();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $userMail]);

        return $this->render('utilisateur/candidat.twig', [
            'user' => $user,
        ]);
    }
}