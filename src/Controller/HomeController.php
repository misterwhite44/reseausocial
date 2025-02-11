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
use App\Form\ModifyPostType;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Entity\Signalement;
use App\Entity\Like;
use DateTime;





class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $compte = $this->getUser();

        // Récupérer les publications
        $publications = $em->getRepository(Post::class)->findBy([], ['date' => 'DESC']);

        // Récupérer le nombre de signalements pour chaque publication
        $signalementsCount = [];
        foreach ($publications as $publication) {
            $signalements = $em->getRepository(Signalement::class)->findBy(['post_id' => $publication->getId()]);
            $signalementsCount[$publication->getId()] = count($signalements);
        }
        // Récupérer les publications avec leurs commentaires associés
        $publicationsWithComments = [];
        foreach ($publications as $publication) {
            $comments = $em->getRepository(Commentaire::class)->findBy(['post_id' => $publication]);
            $publicationsWithComments[$publication->getId()] = ['publication' => $publication, 'comments' => $comments];
        }
// Récupérer les publications avec leurs commentaires associés
        $publicationsWithComments = [];
        $publications = $em->getRepository(Post::class)->findBy([], ['date' => 'DESC']);

        foreach ($publications as $publication) {
            $comments = $em->getRepository(Commentaire::class)->findBy(['post_id' => $publication]);
            $publicationsWithComments[] = ['publication' => $publication, 'commentaires' => $comments];
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'compte' => $compte,
            'publications' => $publications,
            'publicationsWithComments' => $publicationsWithComments,

            'signalementsCount' => $signalementsCount, // Passer le nombre de signalements à Twig
        ]);
    }
    #[Route('/upgrade-to-admin/{id}', name: 'app_upgrade_to_admin')]
    public function upgradeToAdmin(EntityManagerInterface $em, Compte $compte): Response
    {
        // Vérifiez si l'utilisateur actuel est autorisé à effectuer cette action
        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de modifier les rôles.');
        }

        // Mettez à jour les rôles de l'utilisateur pour inclure ROLE_ADMIN
        $roles = $compte->getRoles();
        $roles[] = 'ROLE_ADMIN';
        $compte->setRoles(array_unique($roles));

        // Enregistrez les modifications dans la base de données
        $em->flush();

        // Redirigez l'utilisateur vers une page de confirmation ou une autre page appropriée
        return $this->redirectToRoute('app_home');
    }



    #[Route('/search', name: 'app_search')]
    public function search(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $compte = $this->getUser();

        $search = $request->query->get('search');

        // Récupérer les comptes
        $comptes = $em->getRepository(Compte::class)->createQueryBuilder('c')
            ->where('c.username LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();

        // Récupérer les hashtags
        $hashtags = $em->getRepository(Hashtag::class)->createQueryBuilder('h')
            ->where('h.texte LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();

        // Récupérer les établissements
        $etablissements = $em->getRepository(Etablissement::class)->createQueryBuilder('e')
            ->where('e.nom LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();

        // Récupérer les publications
        $publications = $em->getRepository(Post::class)->createQueryBuilder('p')
            ->where('p.titre LIKE :search OR p.description LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();

        return $this->render('home/search.html.twig', [
            'controller_name' => 'HomeController',
            'comptes' => $comptes,
            'hashtags' => $hashtags,
            'etablissements' => $etablissements,
            'publications' => $publications, // Passer les publications au template Twig
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

        $compte = $this->getUser();

        // Récupérer les hashtags existants
        $hashtags = $em->getRepository(Hashtag::class)->findAll();

        // Récupérer les données soumises
        $titre = $request->request->get('titre');
        $description = $request->request->get('description');
        $dureeRetard = $request->request->get('duree_retard'); // Récupérer la durée du retard depuis le formulaire

        // Créer une nouvelle publication
        $post = new Post();
        $post->setTitre($titre);
        $post->setDescription($description);
        $post->setDate(new \DateTime());

        // Ajouter la durée du retard à l'heure actuelle
        $tempsRetard = new \DateTime();
        $post->setTempsRetard($tempsRetard);
        $post->setCompteId($this->getUser());



        // Enregistrer la publication
        $em->persist($post);
        $em->flush();

        // Rediriger l'utilisateur vers une page de confirmation ou une autre page appropriée
        return $this->redirectToRoute('app_home');
    }





    #[Route('/modify-post/{id}', name:'app_modify_post')]
    public function modifyPost(Request $request, EntityManagerInterface $em, Post $post): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        // Vérifiez si l'utilisateur actuel est celui qui a créé la publication
        if ($this->getUser() !== $post->getCompteId()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de modifier ce post.');
        }

        // Créer un formulaire pour modifier le post
        $form = $this->createForm(ModifyPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Les données soumises sont valides, enregistrez les modifications
            $em->flush();

            // Rediriger vers une autre page après la modification, par exemple la page d'accueil
            return $this->redirectToRoute('app_home');
        }

        // Le formulaire n'a pas encore été soumis ou n'est pas valide, afficher à nouveau le formulaire avec les erreurs
        return $this->render('home/modif_post.html.twig', [
            'form' => $form->createView(),
            'post' => $post, // Passer l'objet $post au modèle Twig
        ]);
    }


    #[Route('/add-comment/{id}', name: 'app_add_comment')]
    public function addComment(Request $request, EntityManagerInterface $em, Post $post): Response
    {
        // Créer une nouvelle instance de Commentaire
        $commentaire = new Commentaire();

        // Créer un formulaire pour ajouter un commentaire
        $commentaireForm = $this->createForm(CommentaireType::class, $commentaire);
        $commentaireForm->handleRequest($request);

        // Vérifier si le formulaire a été soumis et est valide
        if ($commentaireForm->isSubmitted() && $commentaireForm->isValid()) {
            // Associer le commentaire à la publication actuelle
            $commentaire->setPostId($post);

            // Définir la date de création du commentaire
            $currentDate = new DateTime();
            $commentaire->setCreatedAt($currentDate);

            // Associer le commentaire à l'utilisateur connecté
            $commentaire->setCompteId($this->getUser());

            // Enregistrer le commentaire dans la base de données
            $em->persist($commentaire);
            $em->flush();

            // Redirection vers la même page pour éviter les rechargements
            return $this->redirectToRoute('app_home', ['id' => $post->getId()]);
        }

        // Créer une nouvelle réponse avec le contenu du formulaire de commentaire
        return $this->render('home/add_commentaire.html.twig', [
            'commentaireForm' => $commentaireForm->createView(),
            'post' => $post, // Passer le post à Twig
        ]);
    }


    #[Route('/like/{id}', name: 'app_like_post')]
    public function likePost(Request $request, EntityManagerInterface $em, Post $post): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Récupérer l'utilisateur actuel
        $user = $this->getUser();

        // Vérifier si l'utilisateur a déjà aimé ce post
        $existingLike = $em->getRepository(Like::class)->findOneBy(['post_id' => $post, 'compte_id' => $user]);

        // Si l'utilisateur a déjà aimé ce post, retourner un message d'erreur
        if ($existingLike) {
            return new JsonResponse(['success' => false, 'message' => 'Vous avez déjà aimé ce post.']);
        }

        // Créer une instance de Like
        $like = new Like();
        $like->setPostId($post);
        $like->setCompteId($user);

        // Enregistrer le like dans la base de données
        $em->persist($like);
        $em->flush();

        // Retourner une réponse JSON indiquant le succès de l'opération
        return new JsonResponse(['success' => true]);
    }


    #[Route('/signalement/{id}', name: 'app_signalement')]
    public function signalerPost(Request $request, EntityManagerInterface $em, Post $post): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Créer une instance de Signalement
        $signalement = new Signalement();

        // Récupérer l'utilisateur connecté comme signaleur
        $signaleur = $this->getUser();

        // Récupérer l'utilisateur ou l'entité qui a publié le post
        $signale = $post->getCompteId();

        // Récupérer le motif du signalement à partir des données soumises
        $motif = $request->request->get('motif');

        // Définir les attributs du signalement
        $signalement->setMotif($motif);
        $signalement->setSignaleurId($signaleur);
        $signalement->setSignaleId($signale);
        $signalement->setPostId($post);

        // Ajouter le signalement à la base de données
        $em->persist($signalement);
        $em->flush();

        // Répondre avec un message de succès
        return new Response('Signalement bien reçu.');
    }

    #[Route('/delete-post/{id}', name: 'app_delete_post')]
    public function deletePost(Request $request, EntityManagerInterface $em, Post $post): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Vérifiez si l'utilisateur a le droit de supprimer le post
        if ($this->getUser() !== $post->getCompteId()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de supprimer ce post.');
        }

        // Supprimez le post de la base de données
        $em->remove($post);
        $em->flush();

        // Redirigez l'utilisateur vers une page appropriée après la suppression
        return $this->redirectToRoute('app_home');
    }





}
