<?php
// src/Controller/AdminBackofficeController.php
namespace App\Controller;

use App\Controller\ConnectionController;
use App\Form\RegisterFormType;
use App\Form\AddConsultantFormType;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminBackofficeController extends AbstractController
{
    #[Route('/backoffice/Admin/{id}', name:"backofficeAdmin")]
    public function backoffice(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        // fetch l'objet user via son id dans la db
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);
        $error_message = null;
        $validation_message = null;
        $form = null;

        // si user est admin on créé un nouveau formulaire:AddConsultantFormType
        if ($user->getUserType() === 4) {
            $form = $this->createForm(AddConsultantFormType::class);

            // Si le formulaire a été Submit
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // récupère les data du formulaire
                $data = $form->getData();

                // check l'utilisateur correspondant à cette adresse email
                $loging_user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $data['email']]);

                // si l'email existe déjà retourne un message d'erreur dans la page
                if ($loging_user) {
                    $error_message = "L'addresse email ".$data['email']." existe déjà.";
                    return $this->render('default/backoffice.html.twig', [
                        'form' => $form,
                        'errorMessage' => $error_message,
                        'validationMessage' => $validation_message,
                    ]);
                }

                // si les passwords fournis ne sont pas identique retourne un message d'erreur dans la page
                if ( $data['password'] !== $data['confirmPassword']) {
                    $error_message = "Les mots de passe ne correspondent pas. Ils doivent être identique.";
                    return $this->render('utilisateur/administrateur.twig', [
                        'user' => $user,
                        'form' => $form,
                        'errorMessage' => $error_message,
                        'validationMessage' => $validation_message,
                    ]);
                }

                // les informations fournies sont bonnes donc on créé un nouvel utilisateur
                $newUser = new Utilisateur();
                $newUser->setEmail($data['email']);
                $newUser->setPassword($data['password']);
                $newUser->setUserType(3);
                $newUser->setIsValid(true);
                
                // save l'user dans doctrine et execute le sql pour save l'user dans la db
                $entityManager->persist($newUser);
                $entityManager->flush();

                // enregistrement réussi, display un message de confirmation
                $validation_message = "Le compte du consultant a été correctement créé, il est actif immédiatement.";
                return $this->render('utilisateur/administrateur.twig', [
                    'user' => $user,
                    'form' => $form,
                    'errorMessage' => $error_message,
                    'validationMessage' => $validation_message,
                ]);
            }
        }

        return $this->render('utilisateur/administrateur.twig', [
            'user' => $user,
            'errorMessage' => $error_message,
            'validationMessage' => $validation_message,
            'form' => $form,
        ]);
    }
}