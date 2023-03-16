<?php
// src/Controller/RegisterController.php
namespace App\Controller;

use App\Form\RegisterFormType;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends AbstractController
{
    #[Route('/register', name:"register")]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegisterFormType::class);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = new Utilisateur();
            $user->setEmail($data['Email']);
            $user->setPassword($data['Password']);
            $user->setIsValid(false);

            // tell Doctrine you want to (eventually) save the User (no queries yet)
            $entityManager->persist($user);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            //new Response('Saved new user with id '.$user->getId());
            return $this->redirectToRoute('connection');

        }

        return $this->render('default/register.html.twig', [
            'form' => $form,
        ]);
    }
}