<?php
// src/Controller/CandidatBackofficeController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CandidatBackofficeController extends AbstractController
{
    #[Route('/backoffice/candidat{id}', name:"backofficeCandidat")]
    public function backoffice(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        // fetch l'objet user via son id dans la db
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);

        return $this->render('utilisateur/candidat.twig', [
            'user' => $user,
        ]);
    }
}