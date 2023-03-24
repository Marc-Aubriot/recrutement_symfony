<?php
// src/Controller/ValidationCompteController.php
namespace App\Controller;

use App\Form\ValidateCompteFormType;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ValidationCompteController extends AbstractController
{
    #[Route('backoffice/consultant{id}/validationcomptelist/comte{itemid}', name:"compte")]
    public function backoffice(EntityManagerInterface $entityManager, int $id, int $itemid, Request $request): Response
    {
        // initialise les variables
        $validation_message = null;

        // fetch l'objet user (le consultant) via son id dans la db, et fetch l'item (user à valider) de la page dans la db
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);
        $item = $entityManager->getRepository(Utilisateur::class)->find($itemid);

        // initialise le formulaire et y attache l'entité item
        $form = $this->createForm(ValidateCompteFormType::class);

        // Si le formulaire a été Submit le boolén isValid est automatique passé en true
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // récupère les data du formulaire
            $data = $form->getData();

            // si la checkbox est validée, set Is valid = true
            if ($data['checkbox']) {
                $item->setIsvalid(true);
                
                // save l'user à valider dans doctrine et execute le sql pour save l'user dans la db
                $entityManager->persist($item);
                $entityManager->flush();

                // enregistrement réussi, display un message de confirmation et rerender la page
                $validation_message = "Le compte a été correctement validé. ";
                return $this->render('default/components/validationcompte.twig', [
                    'form' => $form,
                    'validationMessage' => $validation_message,
                    'user' => $user,
                    'item' => $item,
                ]);
            }
        }

        // render la page du compte à valider
        return $this->render('default/components/validationcompte.twig', [
            'form' => $form,
            'validationMessage' => $validation_message,
            'user' => $user,
            'item' => $item,
        ]);
    }
}