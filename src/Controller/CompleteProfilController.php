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

class CompleteProfilController extends AbstractController
{
    #[Route('/backoffice/candidat{id}/profil', name:"completeprofil")]
    public function backoffice(EntityManagerInterface $entityManager, int $id, Request $request): Response
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

        }

        return $this->render('default/components/profil.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}