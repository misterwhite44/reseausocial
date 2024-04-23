<?php
namespace App\DTO;

use OpenApi\Attributes as OA;

#[OA\Schema(title: "CompteInfoDTO", description: "DTO représentant les informations d'un compte avec le nombre de posts")]
class Compte_DTO
{
    #[OA\Property(description: "Nom d'utilisateur")]
    public string $username;

    #[OA\Property(description: "Nombre de posts")]
    public int $nombre_posts;

    public function __construct(string $username, int $nombre_posts)
    {
    $this->username = $username;
    $this->nombre_posts = $nombre_posts;
    }
}
