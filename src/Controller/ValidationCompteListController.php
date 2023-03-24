<?php
// src/Controller/ValidationCompteListController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ValidationCompteListController extends AbstractController
{
    #[Route('backoffice/consultant/validation/compte', name:"validationcomptelist")]
    public function backoffice(EntityManagerInterface $entityManager): Response
    {
        // securise le controlleur
        $this->denyAccessUnlessGranted('ROLE_CONSULTANT', null, "erreur 403 custom : zone restreinte aux consultants.");

        // récupères l'User Entity qui est log in, son mail et enfin l'Utilisateur Entity correspondant
        $userSecurity = $this->getUser();
        $userMail = $userSecurity->getUserIdentifier();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $userMail]);

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