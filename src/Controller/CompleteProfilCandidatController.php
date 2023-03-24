<?php
// src/Controller/CompleteProfilController.php
namespace App\Controller;

use App\Form\CompleteProfilCandidatFormType;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\CvUploader;

class CompleteProfilCandidatController extends AbstractController
{
    #[Route('/backoffice/candidat/profile', name:"completeprofilcandidat")]
    public function backofficeCandidat(EntityManagerInterface $entityManager, Request $request, CvUploader $fileUploader): Response
    {
        // securise le controlleur
        $this->denyAccessUnlessGranted('ROLE_CANDIDAT', null, "erreur 403 custom : zone restreinte aux candidats.");

        // récupères l'User Entity qui est log in, son mail et enfin l'Utilisateur Entity correspondant
        $userSecurity = $this->getUser();
        $userMail = $userSecurity->getUserIdentifier();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $userMail]);

        // set les variables de la page
        $form = $this->createForm(CompleteProfilCandidatFormType::class);
        $error_message = null;
        $validation_message = null;

        // Si le formulaire a été Submit
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // récupère les data du formulaire
            $data = $form->getData();

            // si le champ du formulaire est rempli, enregistre le changement dans l'entité Utilisateur
            if ($data['nom'] !== null) {
                $user->setNom($data['nom']);
            }

            if ($data['prenom'] !== null) {
                $user->setPrénom($data['prenom']);
            }

            if ($data['adresse'] !== null) {
                $user->setAdresse($data['adresse']);
            }

            // avec service : CvUploader
            $file = $form->get('cv')->getData();
            if ($file) {
                $newFilename = $fileUploader->upload($file);
                $user->setCv($newFilename);
            } 

            // save l'user à valider dans doctrine et execute le sql pour save l'user dans la db
            $entityManager->persist($user);
            $entityManager->flush();

            // modification des informations réussies, display un message de confirmation
            $validation_message = "Les changements ont étés enregistrés.";
            return $this->render('default/components/profilcandidat.twig', [
                'user' => $user,
                'form' => $form,
                'errorMessage' => $error_message,
                'validationMessage' => $validation_message,
            ]);

        }

        // si tous les champs sont remplis, renvois un message profile complet
        $fieldNom = $user->getNom();
        $fieldPrenom = $user->getPrénom();
        $fieldAdresse = $user->getAdresse();
        $fieldCv = $user->getCv();

        if ($fieldNom && $fieldPrenom && $fieldAdresse && $fieldCv) {
            $validation_message = "Votre profile est compléter à 100%.";
        }
        if (!$fieldCv) {
            $error_message = "Vous devez envoyer un CV pour que votre profile soit à jour.";
        }

        return $this->render('default/components/profilcandidat.twig', [
            'user' => $user,
            'form' => $form,
            'errorMessage' => $error_message,
            'validationMessage' => $validation_message,
        ]);
    }

}