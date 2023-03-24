<?php
// src/Controller/GetMesAnnoncesController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Annonce;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetMesAnnoncesController extends AbstractController
{
    #[Route('backoffice/recruteur/mesannonces', name:"getmesannonces")]
    public function backoffice(EntityManagerInterface $entityManager): Response
    {
        // securise le controlleur
        $this->denyAccessUnlessGranted('ROLE_RECRUTEUR', null, "erreur 403 custom : zone restreinte aux recruteurs.");

        // récupères l'User Entity qui est log in, son mail et enfin l'Utilisateur Entity correspondant
        $userSecurity = $this->getUser();
        $userMail = $userSecurity->getUserIdentifier();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $userMail]);

        // look dans le répertoire une liste des annonces
        $repository = $entityManager->getRepository(Annonce::class);

        // look dans le répertoire pour matching recruteurId = user et ordered par id
        $annonces = $repository->findBy(
            ['recruteurEmail' => $user->getEmail() ],
            ['dateCréation' => 'ASC']
        );

        // render la page de listage des validations à faire 
        return $this->render('default/components/getmesannonces.twig', [
            'user' => $user,
            'annonces' => $annonces,
        ]);
    }
}