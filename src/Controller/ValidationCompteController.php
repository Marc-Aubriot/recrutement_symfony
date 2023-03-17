<?php
// src/Controller/ValidationCompteController.php
namespace App\Controller;

use App\Controller\ConnectionController;
use App\Form\RegisterFormType;
use App\Form\AddConsultantFormType;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ValidationCompteController extends AbstractController
{
    #[Route('backoffice/consultant/validationcompte/{id}', name:"validationcompte")]
    public function backoffice(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        // fetch l'objet user via son id dans la db
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);
      
        return $this->render('default/components/validationcompte.twig', [
            'user' => $user,
        ]);
    }
}