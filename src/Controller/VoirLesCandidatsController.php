<?php
// src/Controller/VoirLesCandidatsController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Candidature;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoirLesCandidatsController extends AbstractController
{
    #[Route('backoffice/recruteur/mesannonces/annonce{itemid}/candidats', name:"voirlescandidats")]
    public function backoffice(EntityManagerInterface $entityManager, int $itemid): Response
    {
        // securise le controlleur
        $this->denyAccessUnlessGranted('ROLE_RECRUTEUR', null, "erreur 403 custom : zone restreinte aux recruteurs.");

        // récupères l'User Entity qui est log in, son mail et enfin l'Utilisateur Entity correspondant
        $userSecurity = $this->getUser();
        $userMail = $userSecurity->getUserIdentifier();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $userMail]);

        // look dans le répertoire une liste des annonces
        $repository = $entityManager->getRepository(Candidature::class);

        // look dans le répertoire les candidatures correspondant à cette annonce
        $candidats = $repository->findBy(
            ['annonceId' => $itemid ],
            ['id' => 'ASC']
        );

        // render la page de listage des validations à faire 
        return $this->render('default/components/voirlescandidats.twig', [
            'user' => $user,
            'candidats' => $candidats,
        ]);
    }
}