<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'like:item']),
        new GetCollection(normalizationContext: ['groups' => 'like:list'])
    ],
    paginationEnabled: false,
)]
#[ORM\Table(name: '`like`')]
class Like
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['like:list', 'like:item'])]

    private ?int $id = null;

    #[ORM\Column(nullable: false)]
    #[Groups(['like:list', 'like:item'])]

    private ?bool $golden = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['like:list', 'like:item'])]

    private ?Post $post_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['like:list', 'like:item'])]

    private ?Compte $compte_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function isGolden(): ?bool
    {
        return $this->golden;
    }

    public function setGolden(bool $golden): static
    {
        $this->golden = $golden;

        return $this;
    }

    public function getPostId(): ?Post
    {
        return $this->post_id;
    }

    public function setPostId(?Post $post_id): static
    {
        $this->post_id = $post_id;

        return $this;
    }

    public function getCompteId(): ?Compte
    {
        return $this->compte_id;
    }

    public function setCompteId(?Compte $compte_id): static
    {
        $this->compte_id = $compte_id;

        return $this;
    }
}
