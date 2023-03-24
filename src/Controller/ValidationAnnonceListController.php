<?php
// src/Controller/ValidationAnnonceListController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Annonce;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ValidationAnnonceListController extends AbstractController
{
    #[Route('backoffice/consultant/validation/annonce', name:"validationannoncelist")]
    public function backoffice(EntityManagerInterface $entityManager): Response
    {
        // securise le controlleur
        $this->denyAccessUnlessGranted('ROLE_CONSULTANT', null, "erreur 403 custom : zone restreinte aux consultants.");

        // récupères l'User Entity qui est log in, son mail et enfin l'Utilisateur Entity correspondant
        $userSecurity = $this->getUser();
        $userMail = $userSecurity->getUserIdentifier();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $userMail]);

        // look dans le répertoire une liste des comptes non validés
        $repository = $entityManager->getRepository(Annonce::class);

        // look dans le répertoire pour matching validation_statut false et ordered par date
        $annonces = $repository->findBy(
            ['validation_statut' => false ],
            ['dateCréation' => 'ASC']
        );

        // render la page de listage des validations à faire 
        return $this->render('default/components/validationannoncelist.twig', [
            'user' => $user,
            'annonces' => $annonces,
        ]);
    }
}