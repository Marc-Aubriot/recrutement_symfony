<?php
// src/Controller/GetMesAnnoncesController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Annonce;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class GetMesAnnoncesController extends AbstractController
{
    #[Route('backoffice/recruteur{id}/mesannonces', name:"getmesannonces")]
    public function backoffice(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        // fetch l'objet user via son id dans la db
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);


        // look dans le répertoire une liste des annonces
        $repository = $entityManager->getRepository(Annonce::class);

        // look dans le répertoire pour matching recruteurId = user et ordered par id
        $annonces = $repository->findBy(
            ['recruteurId' => $user->getId() ],
            ['id' => 'ASC']
        );

        // render la page de listage des validations à faire 
        return $this->render('default/components/getmesannonces.twig', [
            'user' => $user,
            'annonces' => $annonces,
        ]);
    }
}