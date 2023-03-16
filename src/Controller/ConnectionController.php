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
            //$loging_user = $entityManager->getRepository(Utilisateur::class)->find($data['email']);
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
                $error_message = "Le mot de passe ".$data['password']." est incorrect.";
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

            // l'addresse email existe, le password correspond et l'utilisateur est validé et donc peut se connecter
            return $this->render('default/backoffice.html.twig', [

            ]);
        }

        // premier render de la page
        return $this->render('default/connection.html.twig', [
            'form' => $form,
            'errorMessage' => $error_message,
        ]);
    }
}