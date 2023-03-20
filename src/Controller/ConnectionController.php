<?php
// src/Controller/ConnectionController.php
namespace App\Controller;

use App\Form\ConnectionFormType;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ConnectionController extends AbstractController
{
    #[Route('/connection', name:"connection")]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // set les variables de la page
        $form = $this->createForm(ConnectionFormType::class);
        $error_message = null;

        // Si le formulaire a été Submit
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // récupère les data du formulaire
            $data = $form->getData();

            // check l'utilisateur correspondant à cette adresse email
            $loging_user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $data['email']]);

            // si l'email n'existe pas retourne un message d'erreur dans la page
            if (!$loging_user) {
                $error_message = "L'addresse email ".$data['email']." n'existe pas.";
                return $this->render('default/connection.html.twig', [
                    'form' => $form,
                    'errorMessage' => $error_message,
                ]);
            }

            // si le password fourni est différent du password de l'utilisateur retourne un message d'erreur dans la page
            if ($loging_user->getPassword() !== $data['password']) {
                $error_message = "Le mot de passe est incorrect.";
                return $this->render('default/connection.html.twig', [
                    'form' => $form,
                    'errorMessage' => $error_message,
                ]);
            }

            // si l'utilisateur n'est pas encore validé par un consultant retourne un message d'erreur dans la page
            if (!$loging_user->getIsvalid()) {
                $error_message = "Votre compte n'est pas encore activé.";
                return $this->render('default/connection.html.twig', [
                    'form' => $form,
                    'errorMessage' => $error_message,
                ]);
            }

            // l'addresse email existe, le password correspond et l'utilisateur est validé, donc peut se connecter
            // on redirect sur le backoffice avec l'id comme nouvelle route, routing selon le type d'user
            $id = $loging_user->getId();
            $user_type = $loging_user->getUserType();

            // user admin
            if ($user_type === 4) {
                return $this->redirectToRoute("backofficeAdmin", [
                    'id' => $id,
                ]);
            }

            // user consultant
            if ($user_type === 3) {
                return $this->redirectToRoute("backofficeConsultant", [
                    'id' => $id,
                ]);
            }

            // user recruteur
            if ($user_type === 2) {
                return $this->redirectToRoute("backofficeRecruteur", [
                    'id' => $id,
                ]);
            }

            // user candidat
            if ($user_type === 1) {
                return $this->redirectToRoute("backofficeCandidat", [
                    'id' => $id,
                ]);
            }
        }

        // premier render de la page
        return $this->render('default/connection.html.twig', [
            'form' => $form,
            'errorMessage' => $error_message,
        ]);
    }
}