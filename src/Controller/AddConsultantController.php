<?php
// src/Controller/AddConsultantController.php
namespace App\Controller;

use App\Form\AddConsultantFormType;
use App\Entity\Utilisateur;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AddConsultantController extends AbstractController
{
    #[Route('/backoffice/admin/newconsultant', name:"addconsultant")]
    public function backoffice(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        // securise le controlleur
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "erreur 403 custom : zone restreinte aux administrateurs.");

        // récupères l'User Entity qui est log in
        $userSecurity = $this->getUser();

        // récupères l'Utilisateur Entity correspondant à l'adresse mail de User Security
        $userMail = $userSecurity->getUserIdentifier();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $userMail]);

        // set les variables
        $error_message = null;
        $validation_message = null;
        $form = $this->createForm(AddConsultantFormType::class);

        // Si le formulaire a été Submit
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // récupère les data du formulaire
            $data = $form->getData();

            // check l'utilisateur correspondant à cette adresse email
            $consultant_to_register = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $data['email']]);

            // si les passwords fournis ne sont pas identique retourne un message d'erreur dans la page
            if ( $data['password'] !== $data['confirmPassword']) {
                $error_message = "Les mots de passe ne correspondent pas. Ils doivent être identique.";
                return $this->render('default/components/addconsultant.twig', [
                    'user' => $user,
                    'form' => $form,
                    'errorMessage' => $error_message,
                    'validationMessage' => $validation_message,
                ]);
            }

            // si l'email existe déjà retourne un message d'erreur dans la page
            if ($consultant_to_register) {
                $error_message = "L'addresse email ".$data['email']." existe déjà.";
                return $this->render('default/components/addconsultant.twig', [
                    'user' => $user,
                    'form' => $form,
                    'errorMessage' => $error_message,
                    'validationMessage' => $validation_message,
                ]);
            }


            // les informations fournies sont bonnes donc on créé Utilisateur Entity
            $newUser = new Utilisateur();
            $newUser->setEmail($data['email']);
            $newUser->setIsValid(true);

            // on créé aussi User Entity qui contient les informations de sécurité
            $newUserSecurity = new User();
            $newUserSecurity->setEmail($data['email']);
            $newUserSecurity->setRoles(['ROLE_CONSULTANT']);
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
            $validation_message = "Le compte du consultant a été correctement créé, il est actif immédiatement.";
            return $this->render('default/components/addconsultant.twig', [
                'user' => $user,
                'form' => $form,
                'errorMessage' => $error_message,
                'validationMessage' => $validation_message,
            ]);
        }

        return $this->render('default/components/addconsultant.twig', [
            'user' => $user,
            'errorMessage' => $error_message,
            'validationMessage' => $validation_message,
            'form' => $form,
        ]);
    }
}