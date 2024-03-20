<?php

namespace App\Controller;

use App\Entity\Publication;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/home', name: 'app_home')]
    public function number(Request $request): Response
    {
        // Récupérer toutes les publications depuis la base de données
        $publications = $this->entityManager->getRepository(Publication::class)->findAll();

        // Traitement du formulaire lors de la soumission
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $title = $request->request->get('postTitle');
            $description = $request->request->get('postDescription');

            // Créer une nouvelle instance de l'entité Publication et définir les données
            $publication = new Publication();

            // Vérifier si le titre n'est pas nul avant de l'assigner
            if ($title !== null) {
                $publication->setTitre($title);
            } else {
                // Gérer le cas où le titre est null, par exemple en lui attribuant une valeur par défaut
                $publication->setTitre('Titre par défaut');
            }
            
            $publication->setDescription($description);
            $publication->setDateHeure(new \DateTimeImmutable());
            // Vous pouvez également définir d'autres valeurs ici, comme l'utilisateur associé à la publication.

            // Persister la publication dans la base de données
            $this->entityManager->persist($publication);
            $this->entityManager->flush();

            // Rediriger l'utilisateur vers une autre page ou afficher un message de confirmation
            // Redirection vers la page d'accueil par exemple
            return $this->redirectToRoute('app_home');
        }

        // Passer les publications à la vue pour affichage
        return $this->render('home/home.html.twig', [
            'publications' => $publications,
        ]);
    }
}
