<?php

namespace App\Entity;

use App\Repository\EtablissementRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EtablissementRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'etablissement:item']),
        new GetCollection(normalizationContext: ['groups' => 'etablissement:list'])
    ],
    paginationEnabled: false,
)]
class Etablissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['etablissement:list', 'etablissement:item'])]

    private ?int $id = null;

    #[ORM\Column(length: 25)]
    #[Groups(['etablissement:list', 'etablissement:item'])]

    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['etablissement:list', 'etablissement:item'])]

    private ?int $code_postal = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups(['etablissement:list', 'etablissement:item'])]

    private ?string $pays = null;

    #[ORM\ManyToOne]
    #[Groups(['etablissement:list', 'etablissement:item'])]

    private ?Photo $photo_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(?int $code_postal): static
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getPhotoId(): ?Photo
    {
        return $this->photo_id;
    }

    public function setPhotoId(?Photo $photo_id): static
    {
        $this->photo_id = $photo_id;

        return $this;
    }
}
