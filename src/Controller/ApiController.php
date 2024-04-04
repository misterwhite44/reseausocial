<?php

namespace App\Controller;

use App\DTO\Periode_DTO;
use App\Repository\CompteRepository; // Import CompteRepository
use App\Repository\PostRepository; // Import PostRepository
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

#[Route('/api')]
class ApiController extends AbstractController
{
    // Inject CompteRepository
    private CompteRepository $CompteRepository;
    private PostRepository $PostRepository;

    public function __construct(CompteRepository $CompteRepository, PostRepository $PostRepository)
    {
        $this->CompteRepository = $CompteRepository;
        $this->PostRepository = $PostRepository;
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
        $count = $this->CompteRepository->count([]);

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
        $count = $this->PostRepository->count([]);

        // Return the count as JSON response
        return $this->json($count);
    }
}
