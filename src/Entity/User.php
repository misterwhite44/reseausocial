<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $Pseudo = null;

    #[ORM\Column(length: 40)]
    private ?string $Email = null;

    #[ORM\Column(length: 50)]
    private ?string $Password = null;

    #[ORM\OneToOne(mappedBy: 'IDUser', cascade: ['persist', 'remove'])]
    private ?Commentaire $Yes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->Pseudo;
    }

    public function setPseudo(string $Pseudo): static
    {
        $this->Pseudo = $Pseudo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): static
    {
        $this->Password = $Password;

        return $this;
    }

    public function getYes(): ?Commentaire
    {
        return $this->Yes;
    }

    public function setYes(Commentaire $Yes): static
    {
        // set the owning side of the relation if necessary
        if ($Yes->getIDUser() !== $this) {
            $Yes->setIDUser($this);
        }

        $this->Yes = $Yes;

        return $this;
    }
}