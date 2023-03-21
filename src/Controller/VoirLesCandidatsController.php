<?php
// src/Controller/VoirLesCandidatsController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Annonce;
use App\Entity\Candidature;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class VoirLesCandidatsController extends AbstractController
{
    #[Route('backoffice/recruteur{id}/mesannonces/annonce{itemid}/candidats', name:"voirlescandidats")]
    public function backoffice(EntityManagerInterface $entityManager, int $id, int $itemid, Request $request): Response
    {
        // fetch l'objet user via son id dans la db
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);

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