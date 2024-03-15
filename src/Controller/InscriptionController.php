<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    #[Route('/lucky/inscription')]
    public function number(): Response
    {
        $number = random_int(0, 100);

        return $this->render('lucky/inscription.html.twig', [
            'number' => $number,
        ]);
    }
}
