<?php
// src/Controller/ValidationAnnonceListController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Annonce;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ValidationAnnonceListController extends AbstractController
{
    #[Route('backoffice/consultant{id}/validationannoncelist/', name:"validationannoncelist")]
    public function backoffice(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        // fetch l'objet user via son id dans la db
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);

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