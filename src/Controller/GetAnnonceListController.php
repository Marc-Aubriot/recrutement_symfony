<?php
// src/Controller/GetAnnonceListController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Annonce;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class GetAnnonceListController extends AbstractController
{
    #[Route('backoffice/candidat{id}/getannoncelist', name:"getannoncelist")]
    public function backoffice(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        // initialise les variables
        $validation_message = null;

        // fetch l'objet user via son id dans la db
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);

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