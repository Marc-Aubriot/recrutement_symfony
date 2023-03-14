<?php
// src/Controller/AppController.php
namespace App\Controller;

use App\Form\RegisterFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AppController extends AbstractController
{
    #[Route('/')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(RegisterFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            // $task = $form->getData();

            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('task_success');
        }

        return $this->render('default/index.html.twig', [
            'form' => $form,
        ]);


    }

    #[Route('/register')]
    public function register(): Response
    {
        return $this->render('default/register.html.twig', [
            
        ]);

    }

    #[Route('/backoffice')]
    public function backoffice(): Response
    {
        return $this->render('default/backoffice.html.twig', [
            
        ]);

        // ...
    }
}