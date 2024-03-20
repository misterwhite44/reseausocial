<?php
// src/Controller/CommentController.php

namespace App\Controller;

use App\Entity\Publication;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
private $entityManager;

public function __construct(EntityManagerInterface $entityManager)
{
$this->entityManager = $entityManager;
}

#[Route('/comment', name: 'app_comment')]
public function commentPage(): Response
{
// Récupérer toutes les publications depuis la base de données
$publications = $this->entityManager->getRepository(Publication::class)->findAll();

return $this->render('home/comment.html.twig', [
'publications' => $publications,
]);
}

#[Route('/comment/{id}', name: 'app_add_comment')]
public function addComment(Request $request, $id): Response
{
// Traitement de l'ajout de commentaire
// Récupérer les données du formulaire
$commentaire = $request->request->get('commentaire');

// Vous pouvez traiter et enregistrer le commentaire ici

// Rediriger l'utilisateur vers la page d'accueil ou une autre page appropriée
return $this->redirectToRoute('app_home');
}
}
