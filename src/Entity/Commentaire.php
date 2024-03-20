<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\OneToOne(inversedBy: 'Yes', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $IDUser = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Publication $IdPublication = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getIDUser(): ?User
    {
        return $this->IDUser;
    }

    public function setIDUser(User $IDUser): static
    {
        $this->IDUser = $IDUser;

        return $this;
    }

    public function getIdPublication(): ?Publication
    {
        return $this->IdPublication;
    }

    public function setIdPublication(?Publication $IdPublication): static
    {
        $this->IdPublication = $IdPublication;

        return $this;
    }
}
