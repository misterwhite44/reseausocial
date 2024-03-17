<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; // Import de la classe EntityManagerInterface

class InscriptionController extends AbstractController
{
    private $entityManager; // Déclaration de la propriété entityManager

    // Injection du EntityManagerInterface via le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/lucky/inscription')]
    public function number(Request $request): Response
    {
        // Traitement du formulaire lors de la soumission
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $pseudo = $request->request->get('username');
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            // Créer une nouvelle instance de l'entité User et définir les données
            $user = new User();
            $user->setPseudo($pseudo);
            $user->setEmail($email);
            $user->setPassword($password);

            // Persister l'utilisateur dans la base de données
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // Rediriger l'utilisateur vers une autre page après l'inscription
            return $this->redirectToRoute('app_connexion');
        }

        // Si la méthode de requête n'est pas POST, afficher simplement le formulaire
        return $this->render('lucky/inscription.html.twig');
    }
}