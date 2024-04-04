<?php

namespace App\Controller;

use App\DTO\Periode_DTO;
use App\Repository\CompteRepository;
use App\Repository\PostRepository;
use App\Repository\AbonnementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

#[Route('/api')]
class ApiController extends AbstractController
{
    // Inject repositories
    private CompteRepository $compteRepository;
    private PostRepository $postRepository;
    private AbonnementRepository $AbonnementRepository; // Add AbonnementRepository

    public function __construct(CompteRepository $compteRepository, PostRepository $postRepository, AbonnementRepository $AbonnementRepository)
    {
        $this->compteRepository = $compteRepository;
        $this->postRepository = $postRepository;
        $this->AbonnementRepository = $AbonnementRepository; // Assign AbonnementRepository
    }

    #[Route('/GetNombreCreationsCompte', name: 'app_api_compte', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            type: 'integer'
        )
    )]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: Periode_DTO::class),
    )]
    public function GetNombreCreationsCompte(Periode_DTO $periode_DTO): Response
    {
        // Fetch the count of Compte entities
        $count = $this->compteRepository->count([]);

        // Return the count as JSON response
        return $this->json($count);
    }

    #[Route('/GetNombreCreationsPost', name: 'app_api_post', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            type: 'integer'
        )
    )]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: Periode_DTO::class),
    )]
    public function GetNombreCreationsPost(Periode_DTO $periode_DTO): Response
    {
        // Fetch the count of Post entities
        $count = $this->postRepository->count([]);

        // Return the count as JSON response
        return $this->json($count);
    }

    #[Route('/GetNombreAbonnements', name: 'app_api_abonnements', methods: ['POST'])] // Define route for getting number of subscriptions
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            type: 'integer'
        )
    )]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: Periode_DTO::class),
    )]
    public function GetNombreAbonnements(Periode_DTO $periode_DTO): Response
    {
        // Fetch the count of Abonnement entities
        $count = $this->AbonnementRepository->count([]);

        // Return the count as JSON response
        return $this->json($count);
    }
}
