<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToOne(targetEntity: Commentaire::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['post:item'])]
    private ?Commentaire $commentaireId = null;

    #[ORM\OneToMany(mappedBy: "post", targetEntity: Commentaire::class)]
    #[Groups(['post:item'])]
    private Collection $commentaires;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
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
        return $this->commentaireId;
    }

    public function setCommentaireId(?Commentaire $commentaireId): static
    {
        $this->commentaireId = $commentaireId;
        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setPost($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPost() === $this) {
                $commentaire->setPost(null);
            }
        }

        return $this;
    }
}
