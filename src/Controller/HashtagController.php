<?php

namespace App\Controller;

use App\Entity\Hashtag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HashtagController extends AbstractController
{
    #[Route('/create_hashtag', name: 'create_hashtag')]
    public function createHashtag(Request $request): Response
    {
        $hashtag = new Hashtag();

        $form = $this->createFormBuilder($hashtag)
            ->add('texte')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Ici, tu peux effectuer l'action que tu souhaites avec le hashtag
            // Par exemple, sauvegarder dans un fichier ou l'envoyer à une API
            $texte = $form->get('texte')->getData();
            // Fais quelque chose avec le texte du hashtag, par exemple :
            // file_put_contents('hashtags.txt', $texte . PHP_EOL, FILE_APPEND);

            return $this->redirectToRoute('hashtag_success');
        }

        return $this->render('hashtag/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/hashtag_success', name: 'hashtag_success')]
    public function hashtagSuccess(): Response
    {
        return $this->render('hashtag/success.html.twig');
    }
}
