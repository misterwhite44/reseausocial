<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicationRepository::class)]
class Publication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $Titre = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $Description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $DateHeure = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $DureeRetard = null;

    #[ORM\OneToMany(mappedBy: 'IdPublication', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): static
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getDateHeure(): ?\DateTimeImmutable
    {
        return $this->DateHeure;
    }

    public function setDateHeure(\DateTimeImmutable $DateHeure): static
    {
        $this->DateHeure = $DateHeure;

        return $this;
    }

    public function getDureeRetard(): ?\DateTimeImmutable
    {
        return $this->DureeRetard;
    }

    public function setDureeRetard(?\DateTimeImmutable $DureeRetard): static
    {
        $this->DureeRetard = $DureeRetard;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setIdPublication($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdPublication() === $this) {
                $commentaire->setIdPublication(null);
            }
        }

        return $this;
    }
}
