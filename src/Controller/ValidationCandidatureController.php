<?php
// src/Controller/ValidationCandidatureController.php
namespace App\Controller;

use App\Form\ValidateCandidatureFormType;
use App\Entity\Utilisateur;
use App\Entity\Candidature;
use App\Entity\Annonce;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

class ValidationCandidatureController extends AbstractController
{
    #[Route('backoffice/consultant/validation/candidature/{itemid}', name:"validationcandidature")]
    public function backoffice(EntityManagerInterface $entityManager, $itemid, Request $request, MailerInterface $mailer): Response
    {
        // securise le controlleur
        $this->denyAccessUnlessGranted('ROLE_CONSULTANT', null, "erreur 403 custom : zone restreinte aux consultants.");

        // récupères l'User Entity qui est log in, son mail et enfin l'Utilisateur Entity correspondant
        $userSecurity = $this->getUser();
        $userMail = $userSecurity->getUserIdentifier();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $userMail]);

        // initialise les variables
        $validation_message = null;
        $mailBot = $this->getParameter('mailBot');

        // fetch l'item (user à valider) de la page dans la db
        $item = $entityManager->getRepository(Candidature::class)->find($itemid);

        // fetch l'entity du recruteur
        $annonce = $entityManager->getRepository(Annonce::class)->find($item->getAnnonceId());
        $recruteur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $annonce->getRecruteurEmail()]);

        // fetch l'entity du candidat'
        $candidat = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' =>$item->getUserMail()]);


        // initialise le formulaire et y attache l'entité item
        $form = $this->createForm(ValidateCandidatureFormType::class);

        // Si le formulaire a été Submit le boolén isValid est automatique passé en true
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // récupère les data du formulaire
            $data = $form->getData();

            // si la checkbox est validée, set Is valid = true
            if ($data['checkbox']) {
                $item->setValidationCandidature(true);
                
                // save l'user à valider dans doctrine et execute le sql pour save l'user dans la db
                $entityManager->persist($item);
                $entityManager->flush();

                // envoi un mail au recruteur avec les informations du candidat
                $message = "Nouvelle candidature pour votre annonce ".$item->getId()."au poste de ".$item->getAnnonceTitle().". Infos du candidat: ".$item->getUserNom()." ".$item->getUserPrenom()." ".$item->getUserMail()." .";
                $email = (new Email())
                ->from($mailBot)
                ->to($recruteur->getEmail())
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Nouveau candidat pour votre annonce')
                ->text($message)
                ->addPart(new DataPart(new File('uploads/cv/'.$candidat->getCv())));
                //->html('<p>See Twig integration for better HTML integration!</p>');

                $mailer->send($email);

                // enregistrement réussi, display un message de confirmation et rerender la page
                $validation_message = "La candidature est validée.";
                return $this->render('default/components/validationcandidature.twig', [
                    'form' => $form,
                    'validationMessage' => $validation_message,
                    'user' => $user,
                    'item' => $item,
                ]);
            }
        }

        // render la page du compte à valider
        return $this->render('default/components/validationcandidature.twig', [
            'form' => $form,
            'validationMessage' => $validation_message,
            'user' => $user,
            'item' => $item,
        ]);
    }
}