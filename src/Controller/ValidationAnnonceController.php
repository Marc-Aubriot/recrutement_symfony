<?php
// src/Controller/ValidationAnnonceController.php
namespace App\Controller;

use App\Form\ValidateAnnonceFormType;
use App\Entity\Utilisateur;
use App\Entity\Annonce;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ValidationAnnonceController extends AbstractController
{
    #[Route('backoffice/consultant{id}/validationcomptelist/annonce{itemid}', name:"annonce")]
    public function backoffice(EntityManagerInterface $entityManager, int $id, int $itemid, Request $request): Response
    {
        // initialise les variables
        $validation_message = null;

        // fetch l'objet user (le consultant) via son id dans la db, et fetch l'item (annonce à valider) de la page dans la db
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);
        $item = $entityManager->getRepository(Annonce::class)->find($itemid);

        // initialise le formulaire et y attache l'entité item
        $form = $this->createForm(ValidateAnnonceFormType::class);

        // Si le formulaire a été Submit le boolén isValid est automatique passé en true
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // récupère les data du formulaire
            $data = $form->getData();

            // si la checkbox est validée, setValidation_statut = true
            if ($data['checkbox']) {
                $item->setValidationStatut(true);
                
                // save l'user à valider dans doctrine et execute le sql pour save l'user dans la db
                $entityManager->persist($item);
                $entityManager->flush();

                // enregistrement réussi, display un message de confirmation et rerender la page
                $validation_message = "L'annonce est correctement validé. ";
                return $this->render('default/components/validationannonce.twig', [
                    'form' => $form,
                    'validationMessage' => $validation_message,
                    'user' => $user,
                    'item' => $item,
                ]);
            }
        }

        // render la page du compte à valider
        return $this->render('default/components/validationannonce.twig', [
            'form' => $form,
            'validationMessage' => $validation_message,
            'user' => $user,
            'item' => $item,
        ]);
    }
}