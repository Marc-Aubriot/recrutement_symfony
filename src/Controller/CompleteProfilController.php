<?php
// src/Controller/CompleteProfilController.php
namespace App\Controller;

use App\Controller\ConnectionController;
use App\Form\RegisterFormType;
use App\Form\AddConsultantFormType;
use App\Form\CompleteProfilFormType;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\CvUploader;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class CompleteProfilController extends AbstractController
{
    #[Route('/backoffice/candidat{id}/profil', name:"completeprofil")]
    public function backoffice(EntityManagerInterface $entityManager, int $id, Request $request, SluggerInterface $slugger, CvUploader $fileUploader): Response
    {
        // set les variables de la page
        $form = $this->createForm(CompleteProfilFormType::class);
        $error_message = null;
        $validation_message = null;

        // fetch l'objet user via son id dans la db
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);

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

            /*
            $file = $form->get('cv')->getData();
            if ($file) {
                // défini les variables du document
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('cv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'cv' property to store the PDF file name
                // instead of its contents
                $user->setCv($newFilename);
            }
            */


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
            return $this->render('default/components/profil.twig', [
                'user' => $user,
                'form' => $form,
                'errorMessage' => $error_message,
                'validationMessage' => $validation_message,
            ]);

        }

        return $this->render('default/components/profil.twig', [
            'user' => $user,
            'form' => $form,
            'errorMessage' => $error_message,
            'validationMessage' => $validation_message,
        ]);
    }
}