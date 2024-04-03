<?php

namespace App\Entity;

use App\Repository\HashtagRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HashtagRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'hashtag:item']),
        new GetCollection(normalizationContext: ['groups' => 'hashtag:list'])
    ],
    paginationEnabled: false,
)]
class Hashtag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['hashtag:list', 'hashtag:item'])]

    private ?int $id = null;

    #[ORM\Column(length: 25)]
    #[Groups(['hashtag:list', 'hashtag:item'])]

    private ?string $texte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): static
    {
        $this->texte = $texte;

        return $this;
    }
}
