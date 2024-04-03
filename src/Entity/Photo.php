<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'photo:item']),
        new GetCollection(normalizationContext: ['groups' => 'photo:list'])
    ],
    paginationEnabled: false,
)]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['photo:list', 'photo:item'])]

    private ?int $id = null;

    #[ORM\Column(type: Types::BLOB)]
    #[Groups(['photo:list', 'photo:item'])]

    private $donnees_photo = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['photo:list', 'photo:item'])]

    private ?Format $format_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getDonneesPhoto()
    {
        return $this->donnees_photo;
    }

    public function setDonneesPhoto($donnees_photo): static
    {
        $this->donnees_photo = $donnees_photo;

        return $this;
    }

    public function getFormatId(): ?Format
    {
        return $this->format_id;
    }

    public function setFormatId(?Format $format_id): static
    {
        $this->format_id = $format_id;

        return $this;
    }
}
