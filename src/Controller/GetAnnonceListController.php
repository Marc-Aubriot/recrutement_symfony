<?php
// src/Controller/GetAnnonceListController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Annonce;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetAnnonceListController extends AbstractController
{
    #[Route('backoffice/candidat/annonce/list', name:"getannoncelist")]
    public function backoffice(EntityManagerInterface $entityManager): Response
    {
        // securise le controlleur
        $this->denyAccessUnlessGranted('ROLE_CANDIDAT', null, "erreur 403 custom : zone restreinte aux candidats.");

        // récupères l'User Entity qui est log in, son mail et enfin l'Utilisateur Entity correspondant
        $userSecurity = $this->getUser();
        $userMail = $userSecurity->getUserIdentifier();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $userMail]);

        // initialise les variables
        $validation_message = null;

        // look dans le répertoire une liste des comptes non validés
        $repository = $entityManager->getRepository(Annonce::class);

        // look dans le répertoire pour matching validation_statut true et ordered par date
        $annonces = $repository->findBy(
            ['validation_statut' => true ],
            ['dateCréation' => 'ASC']
        );

        // render la page de listage des validations à faire 
        return $this->render('default/components/getannoncelist.twig', [
            'validationMessage' => $validation_message,
            'user' => $user,
            'annonces' => $annonces,
        ]);
    }
}