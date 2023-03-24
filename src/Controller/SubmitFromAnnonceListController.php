<?php
// src/Controller/SubmitFromAnnonceListController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Annonce;
use App\Entity\Candidature;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubmitFromAnnonceListController extends AbstractController
{
    #[Route('backoffice/candidat/validation/compte/list/submitcandidatureforannonce/{itemid}', name:"submitcandidature")]
    public function backoffice(EntityManagerInterface $entityManager, int $itemid): Response
    {
        // securise le controlleur
        $this->denyAccessUnlessGranted('ROLE_CANDIDAT', null, "erreur 403 custom : zone restreinte aux candidats.");

        // récupères l'User Entity qui est log in, son mail et enfin l'Utilisateur Entity correspondant
        $userSecurity = $this->getUser();
        $userMail = $userSecurity->getUserIdentifier();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $userMail]);

        // initialise les variables
        $validation_message = null;

        // fetch l'objet user (le candidat) via son id dans la db, et fetch l'item (annonce à visionner) de la page dans la db
        $item = $entityManager->getRepository(Annonce::class)->find($itemid);
        
         // look dans le répertoire une liste des comptes non validés
         $repository = $entityManager->getRepository(Annonce::class);


         // look dans le répertoire pour matching validation_statut true et ordered par date
         $annonces = $repository->findBy(
             ['validation_statut' => true ],
             ['dateCréation' => 'ASC']
         );

        // créer une nouvelle candidature
        $candidature = new Candidature();
        $candidature->setAnnonceId($item->getId());
        $candidature->setUserId($user->getId());
        $time = new \DateTime();
        $time->format('H:i:s \O\n Y-m-d');
        $candidature->setDateCandidature($time);
        $candidature->setValidationCandidature(0);
        $candidature->setUserNom($user->getNom());
        $candidature->setUserPrenom($user->getPrénom());
        $candidature->setUserMail($user->getEmail());
        $candidature->setUserIsValid($user->getIsValid());
        $candidature->setAnnonceTitle($item->getIntitulé());
        $candidature->setAnnonceNomEntreprise($item->getNomEntreprise());
        $candidature->setAnnonceDate($item->getDateCréation());
        $candidature->setAnnonceIsValid($item->isValidationStatut());

        // save l'user à valider dans doctrine et execute le sql pour save l'user dans la db
        $entityManager->persist($candidature);
        $entityManager->flush();

        // candidature déposée display un message de confirmation et rerender la page
        $validation_message = "Votre candidature pour le poste de ". $item->getIntitulé() ." de l'annonce ". $item->getId() ." est envoyée.";
        return $this->render('default/components/getannoncelist.twig', [
            'validationMessage' => $validation_message,
            'user' => $user,
            'item' => $item,
            'annonces' => $annonces,
        ]);
    }
}