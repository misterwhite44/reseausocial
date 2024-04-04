<?php

namespace App\DTO;
use OpenApi\Attributes as OA;

#[OA\Schema(title: "Periode_DTO", description: "Periode DTO")]
class Periode_DTO
{
    #[OA\Property(description: "Date de début")]
    public string $dateDebut;
    #[OA\Property(description: "Date de fin")]
    public string $dateFin;
}
