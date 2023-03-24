<?php
// src/Controller/RegisterController.php
namespace App\Controller;

use App\Form\RegisterFormType;
use App\Entity\Utilisateur;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name:"register")]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // set les variables de la page
        $form = $this->createForm(RegisterFormType::class);
        $error_message = null;
        $validation_message = null;

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
                return $this->render('default/register.html.twig', [
                    'form' => $form,
                    'errorMessage' => $error_message,
                    'validationMessage' => $validation_message,
                ]);
            }

            // si les passwords fournis ne sont pas identique retourne un message d'erreur dans la page
            if ( $data['password'] !== $data['confirmPassword']) {
                $error_message = "Les mots de passe ne correspondent pas. Ils doivent être identique.";
                return $this->render('default/register.html.twig', [
                    'form' => $form,
                    'errorMessage' => $error_message,
                    'validationMessage' => $validation_message,
                ]);
            }

            // si les checkbox sont vides retourne un message d'erreur dans la plage
            if ( $data['checkboxCandidat'] !== true && $data['checkboxRecruteur'] !== true ) {
                $error_message = "Vous n'avez pas cocher de case candidat ou recruteur.";
                return $this->render('default/register.html.twig', [
                    'form' => $form,
                    'errorMessage' => $error_message,
                    'validationMessage' => $validation_message,
                ]);
            }

            // si les checkbox sont pleines retourne un message d'erreur dans la plage
            if ( $data['checkboxCandidat'] === true && $data['checkboxRecruteur'] === true ) {
                $error_message = "Vous ne pouvez cocher qu'une seule case candidat ou recruteur.";
                return $this->render('default/register.html.twig', [
                    'form' => $form,
                    'errorMessage' => $error_message,
                    'validationMessage' => $validation_message,
                ]);
            }

            // défini le type d'utilisateur
            //if ( $data['checkboxCandidat'] === true ) {
            $user_type = $data['checkboxCandidat'] ? ['ROLE_CANDIDAT'] : ['ROLE_RECRUTEUR'];
   
            // les informations fournies sont bonnes donc on créé Utilisateur Entity
            $newUser = new Utilisateur();
            $newUser->setEmail($data['email']);
            $newUser->setIsValid(false);

            // on créé aussi User Entity qui contient les informations de sécurité
            $newUserSecurity = new User();
            $newUserSecurity->setEmail($data['email']);
            $newUserSecurity->setRoles($user_type);
            // hash le password
            $plaintextPassword = $data['password'];
            $hashedPassword = $passwordHasher->hashPassword(
                $newUserSecurity,
                $plaintextPassword
            );
            $newUserSecurity->setPassword($hashedPassword);

            // save l'user dans doctrine et execute le sql pour save l'user dans la db
            $entityManager->persist($newUser);
            $entityManager->persist($newUserSecurity);
            $entityManager->flush();

            // enregistrement réussi, display un message de confirmation
            $validation_message = "Votre compte a été correctement créé. Il devra d'abord être validé par un de nos consultant avant que vous puissiez vous connecter. Essayez de vous connecter plus tard.";
            return $this->render('default/register.html.twig', [
                'form' => $form,
                'errorMessage' => $error_message,
                'validationMessage' => $validation_message,
            ]);

        }

        return $this->render('default/register.html.twig', [
            'form' => $form,
            'errorMessage' => $error_message,
            'validationMessage' => $validation_message,
        ]);
    }
}