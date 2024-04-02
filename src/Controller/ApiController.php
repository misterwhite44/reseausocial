<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/data", name="api_data", methods={"GET"})
     */
    public function getData(): JsonResponse
    {
        // Récupérer les données depuis la base de données ou un service
        $data = [
            'example' => 'data',
            'another_example' => 'more_data',
        ];

        // Serializer pour sérialiser les données en JSON
        $json = $this->get('serializer')->serialize($data, 'json');

        // Créer une réponse JSON
        return new JsonResponse($json, 200, [], true);
    }
}
