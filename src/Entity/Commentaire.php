<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'commentaire:item']),
        new GetCollection(normalizationContext: ['groups' => 'commentaire:list'])
    ],
    paginationEnabled: false,
)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['commentaire:list', 'commentaire:item'])]

    private ?int $id = null;

    #[ORM\Column(length: 2200)]
    #[Groups(['commentaire:list', 'commentaire:item'])]

    private ?string $texte = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['commentaire:list', 'commentaire:item'])]

    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['commentaire:list', 'commentaire:item'])]

    private ?Post $post_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['commentaire:list', 'commentaire:item'])]

    private ?Compte $compte_id = null;

    #[ORM\OneToMany(mappedBy: 'CommentaireId', targetEntity: Post::class)]
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
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

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): static
    {
        $this->texte = $texte;

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

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setCommentaireId($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCommentaireId() === $this) {
                $post->setCommentaireId(null);
            }
        }

        return $this;
    }
}
