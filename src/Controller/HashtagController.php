<?php

namespace App\Controller;

use App\Entity\Hashtag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HashtagController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/create_hashtag', name: 'create_hashtag')]
    public function createHashtag(Request $request): Response
    {
        $hashtag = new Hashtag();

        $form = $this->createFormBuilder($hashtag)
            ->add('texte')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $texte = $form->get('texte')->getData();

            // Définit le texte du hashtag dans l'objet Hashtag
            $hashtag->setTexte($texte);

            // Persiste et flush l'objet Hashtag
            $this->entityManager->persist($hashtag);
            $this->entityManager->flush();

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


    #[Route('/hashtags', name: 'hashtags')]
    public function listHashtags(EntityManagerInterface $entityManager): Response
    {
        // Récupère tous les hashtags depuis la base de données
        $hashtags = $entityManager->getRepository(Hashtag::class)->findAll();

        // Passe les hashtags à la vue pour les afficher
        return $this->render('hashtag/show.html.twig', [
            'hashtags' => $hashtags,
        ]);
    }

}
