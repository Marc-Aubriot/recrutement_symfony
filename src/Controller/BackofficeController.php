<?php
// src/Controller/BackofficeController.php
namespace App\Controller;

use App\Controller\ConnectionController;
use App\Form\RegisterFormType;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BackofficeController extends AbstractController
{
    #[Route('/backoffice/{id}', name:"backoffice")]
    public function backoffice(EntityManagerInterface $entityManager, int $id): Response
    {

        // fetch l'objet user via son id dans la db
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);

        return $this->render('default/backoffice.html.twig', [
            'user' => $user,
        ]);
    }
}