<?php
// src/Controller/ValidationCandidatureListController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Candidature;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ValidationCandidatureListController extends AbstractController
{
    #[Route('backoffice/consultant{id}/validationcandidaturelist/', name:"validationcandidaturelist")]
    public function backoffice(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        // fetch l'objet user via son id dans la db
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);


        // look dans le répertoire une liste des comptes non validés
        $repository = $entityManager->getRepository(Candidature::class);

        // look dans le répertoire pour matching validationCandidature false et ordered par id
        $candidatures = $repository->findBy(
            ['validationCandidature' => false ],
            ['dateCandidature' => 'ASC']
        );

        // render la page de listage des validations à faire 
        return $this->render('default/components/validationcandidaturelist.twig', [
            'user' => $user,
            'candidatures' => $candidatures,
        ]);
    }
}