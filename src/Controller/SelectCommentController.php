<?php
// src/Controller/SelectCommentController.php

namespace App\Controller;

use App\Entity\Publication;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SelectCommentController extends AbstractController
{
#[Route('/comment/{id}', name: 'app_select_comment')]
public function selectComment(Request $request, $id): Response
{
// Récupérer l'entity manager pour accéder à la base de données
$entityManager = $this->getDoctrine()->getManager();

// Récupérer la publication spécifiée par son ID
$publication = $entityManager->getRepository(Publication::class)->find($id);

// Vérifier si la publication existe
if (!$publication) {
throw $this->createNotFoundException('La publication spécifiée n\'existe pas');
}

// Récupérer tous les commentaires liés à cette publication
$commentaires = $publication->getCommentaires();

return $this->render('home/select_comment.html.twig', [
'publication' => $publication,
'commentaires' => $commentaires,
]);
}

#[Route('/comment/edit/{commentId}', name: 'app_edit_comment')]
public function editComment(Request $request, $commentId): Response
{
// Récupérer l'entity manager pour accéder à la base de données
$entityManager = $this->getDoctrine()->getManager();

// Exemple de récupération d'un commentaire sans utiliser Doctrine
// Vous devrez adapter cette logique à votre implémentation spécifique de l'accès aux données
$commentaire = $entityManager->find(Commentaire::class, $commentId);

// Traiter la modification du commentaire ici
// ...

// Rediriger l'utilisateur vers la page de sélection des commentaires pour la publication
return $this->redirectToRoute('app_select_comment', ['id' => $commentaire->getPublication()->getId()]);
}
}
