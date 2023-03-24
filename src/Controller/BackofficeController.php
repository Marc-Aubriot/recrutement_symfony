<?php
// src/Controller/BackofficeController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackofficeController extends AbstractController
{
    #[Route('/backoffice', name:"backoffice")]
    public function backoffice(EntityManagerInterface $entityManager): Response
    {
        // récupères l'User Entity qui est log in
        $userSecurity = $this->getUser();

        // récupères l'Utilisateur Entity correspondant à l'adresse mail de User Security
        $userMail = $userSecurity->getUserIdentifier();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $userMail]);

        // récupère l'array contenant les ROLES de l'User Entity qui est log in
        $userSecurityRole = $userSecurity->getRoles();

        if ( $user->getIsValid() ) {
            if ( $userSecurityRole[0] == 'ROLE_ADMIN' ) {
                return $this->redirectToRoute("backofficeAdmin", [
                    'user' => $user,
                ]);
            }

            if ( $userSecurityRole[0] == 'ROLE_CONSULTANT' ) {
                return $this->redirectToRoute("backofficeConsultant", [
                    'user' => $user,
                ]);
            }

            if ( $userSecurityRole[0] == 'ROLE_RECRUTEUR' ) {
                return $this->redirectToRoute("backofficeRecruteur", [
                    'user' => $user,
                ]);
            }

            if ( $userSecurityRole[0] == 'ROLE_CANDIDAT' ) {
                return $this->redirectToRoute("backofficeCandidat", [
                    'user' => $user,
                ]);
            }
        }

        return $this->redirectToRoute("attentevalidation", [
            'user' => $user,
        ]);
    }
}