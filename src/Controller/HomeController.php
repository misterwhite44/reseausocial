<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Compte;
use App\Entity\Post;
use App\Entity\Hashtag;
use App\Entity\Abonnement;
use App\Entity\Etablissement;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');


        $compte = $this->getUser();

        $publications = $em->getRepository(Post::class)->findBy([], ['date' => 'DESC']);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'compte' => $compte,
            'publications' => $publications,
        ]);
    }


    #[Route('/search', name: 'app_search')]
    public function search(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $compte = $this->getUser();

        $search = $request->query->get('search');


        $comptes = $em->getRepository(Compte::class)->createQueryBuilder('c')
            ->where('c.username LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();

        foreach ($comptes as $compte) {
            $photo = $compte->getPhotoId();
            if ($photo) {
                $compte->photo = [
                    'donneesPhoto' => base64_encode(stream_get_contents($photo->getDonneesPhoto())),
                    'format' => $photo->getFormatId()->getNom(),
                ];
            } else {
                $compte->photo = null;
            }

            $isSubscribed = $em->getRepository(Abonnement::class)->findOneBy([
                'suiveur_id' => $this->getUser(),
                'suivi_personne_id' => $compte
            ]);

            // Ajouter une nouvelle propriété à chaque compte pour indiquer s'il est abonné
            $compte->isSubscribed = $isSubscribed ? true : false;
        }

        $hashtags = $em->getRepository(Hashtag::class)->createQueryBuilder('h')
            ->where('h.texte LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();

        $etablissements = $em->getRepository(Etablissement::class)->createQueryBuilder('e')
            ->where('e.nom LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();


        return $this->render('home/search.html.twig', [
            'controller_name' => 'HomeController',
            'comptes' => $comptes,
            'hashtags' => $hashtags,
            'etablissements' => $etablissements,
        ]);
    }


    #[Route('/autocomplete', name: 'app_autocomplete', methods: ['GET'])]
    public function autocomplete(Request $request, EntityManagerInterface $em): Response
    {
        $search = $request->query->get('search');

        // Effectuez votre recherche pour obtenir les suggestions d'autocomplétion
        $suggestions = $em->getRepository(Compte::class)->createQueryBuilder('c')
            ->where('c.username LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();

        $suggestions += $em->getRepository(Hashtag::class)->createQueryBuilder('h')
            ->where('h.texte LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();

        $suggestions += $em->getRepository(Etablissement::class)->createQueryBuilder('e')
            ->where('e.nom LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();

        // On veut garder uniquement les noms des comptes, hashtags et établissements
        $suggestions = array_map(function($suggestion) {
            return $suggestion->getUsername() ?? $suggestion->getTexte() ?? $suggestion->getNom();
        }, $suggestions);

        return new JsonResponse($suggestions);
    }
    #[Route('/create-post', name: 'app_create_post')]
    public function createPost(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Récupérer les données soumises
        $titre = $request->request->get('titre');
        $description = $request->request->get('description');

        // Créer une nouvelle publication
        $post = new Post();
        $post->setTitre($titre);
        $post->setDescription($description);
        $post->setDate(new \DateTime());
        $post->setTempsRetard(new \DateTime());
        $post->setCompteId($this->getUser());

        // Enregistrer la publication
        $em->persist($post);
        $em->flush();

        // Rediriger l'utilisateur vers une page de confirmation ou une autre page appropriée
        return $this->redirectToRoute('app_home');
    }

}
