<?php
// src/Controller/GetCandidatureController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Candidature;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetCandidatureController extends AbstractController
{
    #[Route('backoffice/candidat/mescandidatures/', name:"getcandidature")]
    public function backoffice(EntityManagerInterface $entityManager): Response
    {
        // securise le controlleur
        $this->denyAccessUnlessGranted('ROLE_CANDIDAT', null, "erreur 403 custom : zone restreinte aux candidats.");

        // récupères l'User Entity qui est log in, son mail et enfin l'Utilisateur Entity correspondant
        $userSecurity = $this->getUser();
        $userMail = $userSecurity->getUserIdentifier();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $userMail]);

        // look dans le répertoire une liste des comptes non validés
        $repository = $entityManager->getRepository(Candidature::class);

        // look dans le répertoire pour matching userId = user false et ordered par date
        $candidatures = $repository->findBy(
            ['userMail' => $user->getEmail() ],
            ['dateCandidature' => 'ASC']
        );
        
        // render la page de listage des validations à faire 
        return $this->render('default/components/getcandidature.twig', [
            'user' => $user,
            'candidatures' => $candidatures,
        ]);
    }
}