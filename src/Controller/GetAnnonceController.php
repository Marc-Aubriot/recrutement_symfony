<?php
// src/Controller/GetAnnonceController.php
namespace App\Controller;

use App\Form\SubmitCandidatureFormType;
use App\Entity\Utilisateur;
use App\Entity\Annonce;
use App\Entity\Candidature;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class GetAnnonceController extends AbstractController
{
    #[Route('backoffice/candidat/validation/compte/list/{itemid}', name:"getannonce")]
    public function backoffice(EntityManagerInterface $entityManager, $itemid, Request $request): Response
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

        // initialise le formulaire de candidature
        $form = $this->createForm(SubmitCandidatureFormType::class);

        // Si le formulaire a été Submit
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            // créer une nouvelle candidature
            $candidature = new Candidature();
            $candidature->setAnnonceId($item->getId());
            $time = new \DateTime();
            $time->format('H:i:s \O\n Y-m-d');
            $candidature->setDateCandidature($time);
            $candidature->setValidationCandidature(0);
            $candidature->setUserNom($user->getNom());
            $candidature->setUserPrenom($user->getPrénom());
            $candidature->setUserMail($user->getEmail());
            $candidature->setAnnonceTitle($item->getIntitulé());
            $candidature->setAnnonceNomEntreprise($item->getNomEntreprise());
            $candidature->setAnnonceDate($item->getDateCréation());

            // save l'user à valider dans doctrine et execute le sql pour save l'user dans la db
            $entityManager->persist($candidature);
            $entityManager->flush();

            // candidature déposée display un message de confirmation et rerender la page
            $validation_message = "Votre candidature pour ce poste est envoyée.";
            return $this->render('default/components/getannonce.twig', [
                'form' => $form,
                'validationMessage' => $validation_message,
                'user' => $user,
                'item' => $item,
            ]);
        }
        

        // render la page du compte à valider
        return $this->render('default/components/getannonce.twig', [
            'form' => $form,
            'validationMessage' => $validation_message,
            'user' => $user,
            'item' => $item,
        ]);
    }
}