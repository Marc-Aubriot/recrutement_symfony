<?php
// src/Controller/ValidationCompteListController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ValidationCompteListController extends AbstractController
{
    #[Route('backoffice/consultant{id}/validationcomptelist/', name:"validationcomptelist")]
    public function backoffice(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        // fetch l'objet user via son id dans la db
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);


        // look dans le répertoire une liste des comptes non validés
        $repository = $entityManager->getRepository(Utilisateur::class);

        // look dans le répertoire pour matching is_valid false et ordered par id (a remplacer par date de création par la suite)
        $users = $repository->findBy(
            ['isValid' => false ],
            ['id' => 'ASC']
        );

        // render la page de listage des validations à faire 
        return $this->render('default/components/validationcomptelist.twig', [
            'user' => $user,
            'users' => $users,
        ]);
    }
}