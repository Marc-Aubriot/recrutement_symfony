<?php
// src/Controller/CompleteProfilController.php
namespace App\Controller;

use App\Form\CompleteProfilRecruteurFormType;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CompleteProfilRecruteurController extends AbstractController
{

    #[Route('/backoffice/recruteur/profile', name:"completeprofilrecruteur")]
    public function backofficeRecruteur(EntityManagerInterface $entityManager, Request $request): Response
    {
        // securise le controlleur
        $this->denyAccessUnlessGranted('ROLE_RECRUTEUR', null, "erreur 403 custom : zone restreinte aux recruteurs.");

        // récupères l'User Entity qui est log in, son mail et enfin l'Utilisateur Entity correspondant
        $userSecurity = $this->getUser();
        $userMail = $userSecurity->getUserIdentifier();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $userMail]);

        // set les variables de la page
        $form = $this->createForm(CompleteProfilRecruteurFormType::class);
        $error_message = null;
        $validation_message = null;

        // Si le formulaire a été Submit
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // récupère les data du formulaire
            $data = $form->getData();

            // si le champ du formulaire est rempli, enregistre le changement dans l'entité Utilisateur
            if ($data['nomentreprise'] !== null) {
                $user->setNom($data['nomentreprise']);
            }

            if ($data['adresseentreprise'] !== null) {
                $user->setAdresse($data['adresseentreprise']);
            }

            // save l'user à valider dans doctrine et execute le sql pour save l'user dans la db
            $entityManager->persist($user);
            $entityManager->flush();

            // modification des informations réussies, display un message de confirmation
            $validation_message = "Les changements ont étés enregistrés.";
            return $this->render('default/components/profilrecruteur.twig', [
                'user' => $user,
                'form' => $form,
                'errorMessage' => $error_message,
                'validationMessage' => $validation_message,
            ]);

        }

        // si tous les champs sont remplis, renvois un message profile complet
        $fieldNom = $user->getNom();
        $fieldAdresse = $user->getAdresse();

        if ($fieldNom && $fieldAdresse) {
            $validation_message = "Votre profile est compléter à 100%.";
        }

        return $this->render('default/components/profilrecruteur.twig', [
            'user' => $user,
            'form' => $form,
            'errorMessage' => $error_message,
            'validationMessage' => $validation_message,
        ]);
    }
}