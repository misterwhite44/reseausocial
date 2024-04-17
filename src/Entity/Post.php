<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'post:item']),
        new GetCollection(normalizationContext: ['groups' => 'post:list'])
    ],
    paginationEnabled: false,
)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['post:list', 'post:item'])]

    private ?int $id = null;

    #[ORM\Column(length: 75, nullable: true)]
    #[Groups(['post:list', 'post:item'])]

    private ?string $titre = null;

    #[ORM\Column(length: 2200, nullable: true)]
    #[Groups(['post:list', 'post:item'])]

    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['post:list', 'post:item'])]

    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['post:list', 'post:item'])]

    private ?\DateTimeInterface $temps_retard = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post:list', 'post:item'])]

    private ?Compte $compte_id = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?Commentaire $commentaire = null;

    public function getCommentaire(): ?Commentaire
    {
        return $this->commentaire;
    }

    public function setCommentaire(?Commentaire $commentaire): static
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTempsRetard(): ?\DateTimeInterface
    {
        return $this->temps_retard;
    }

    public function setTempsRetard(\DateTimeInterface $temps_retard): static
    {
        $this->temps_retard = $temps_retard;

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

    public function getCommentaireId(): ?Commentaire
    {
        return $this->CommentaireId;
    }

    public function setCommentaireId(?Commentaire $CommentaireId): static
    {
        $this->CommentaireId = $CommentaireId;

        return $this;
    }
}
