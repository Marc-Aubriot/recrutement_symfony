<?php
// src/Controller/AddAnnonceController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Annonce;
use App\Form\AddAnnonceFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AddAnnonceController extends AbstractController
{
    #[Route('/backoffice/recruteur/newannonce', name:"addannonce")]
    public function backoffice(EntityManagerInterface $entityManager, Request $request): Response
    {
        // securise le controlleur
        $this->denyAccessUnlessGranted('ROLE_RECRUTEUR', null, "erreur 403 custom : zone restreinte aux recruteurs.");

        // récupères l'User Entity qui est log in, son mail et enfin l'Utilisateur Entity correspondant
        $userSecurity = $this->getUser();
        $userMail = $userSecurity->getUserIdentifier();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $userMail]);

        // set les variables de la page
        $form = $this->createForm(AddAnnonceFormType::class);
        $error_message = null;
        $validation_message = null;

        // Si le formulaire a été Submit
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // récupère les data du formulaire
            $data = $form->getData();
            $annonce = new Annonce();
            $annonce->setRecruteurId($user->getId());
            $annonce->setIntitulé($data['title']);
            $annonce->setNomEntreprise($user->getNom());
            $annonce->setAdresseEntreprise($user->getAdresse());
            $annonce->setDescription($data['description']);
            $time = new \DateTime();
            $time->format('H:i:s \O\n Y-m-d');
            $annonce->setDateCréation($time);
            $annonce->setValidationStatut(0);

            // si le champ du formulaire est rempli, enregistre le changement dans l'entité Utilisateur
            if ($data['horaires'] !== null) {
                $annonce->setHoraires($data['horaires']);
            }

            if ($data['salaire'] !== null) {
                $annonce->setSalaire($data['salaire']);
            }

            // save l'user à valider dans doctrine et execute le sql pour save l'user dans la db
            $entityManager->persist($annonce);
            $entityManager->flush();

            // modification des informations réussies, display un message de confirmation
            $validation_message = "Votre annonce est correctement remplie et sera publiée après validation par un de nos consultants.";
            return $this->render('default/components/addannonce.twig', [
                'user' => $user,
                'form' => $form,
                'errorMessage' => $error_message,
                'validationMessage' => $validation_message,
            ]);

        }

        return $this->render('default/components/addannonce.twig', [
            'user' => $user,
            'form' => $form,
            'errorMessage' => $error_message,
            'validationMessage' => $validation_message,
        ]);
    }
}
