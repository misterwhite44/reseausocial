<?php

namespace App\Entity;

use App\Repository\CompteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
        operations: [
            new Get(normalizationContext: ['groups' => 'compte:item']),
            new GetCollection(normalizationContext: ['groups' => 'compte:list'])
       ],
    paginationEnabled: false,
)]

#[ORM\Entity(repositoryClass: CompteRepository::class)]
class Compte implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['compte:list', 'compte:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['compte:list', 'compte:item'])]

    private ?string $username = null;

    #[ORM\Column]
    #[Groups(['compte:list', 'compte:item'])]

    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['compte:list', 'compte:item'])]

    private ?string $password = null;

    #[ORM\ManyToOne]
    #[Groups(['compte:list', 'compte:item'])]

    private ?Photo $photo_id = null;

    #[ORM\ManyToOne]
    #[Groups(['compte:list', 'compte:item'])]

    private ?Etablissement $etablissement_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['compte:list', 'compte:item'])]

    private ?string $biographie = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['compte:list', 'compte:item'])]

    private ?\DateTimeInterface $dernier_goldden_like = null;

    #[ORM\Column(length: 255)]
    #[Groups(['compte:list', 'compte:item'])]

    private ?string $email = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(['compte:list', 'compte:item'])]

    private ?string $nom_affichage = null;

    #[ORM\Column]
    #[Groups(['compte:list', 'compte:item'])]

    private ?bool $suspendu = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getEtablissementId(): ?Etablissement
    {
        return $this->etablissement_id;
    }

    public function setEtablissementId(?Etablissement $etablissement_id): static
    {
        $this->etablissement_id = $etablissement_id;

        return $this;
    }

    public function getBiographie(): ?string
    {
        return $this->biographie;
    }

    public function setBiographie(?string $biographie): static
    {
        $this->biographie = $biographie;

        return $this;
    }

    public function getDernierGolddenLike(): ?\DateTimeInterface
    {
        return $this->dernier_goldden_like;
    }

    public function setDernierGolddenLike(?\DateTimeInterface $dernier_goldden_like): static
    {
        $this->dernier_goldden_like = $dernier_goldden_like;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNomAffichage(): ?string
    {
        return $this->nom_affichage;
    }

    public function setNomAffichage(?string $nom_affichage): static
    {
        $this->nom_affichage = $nom_affichage;

        return $this;
    }

    public function isSuspendu(): ?bool
    {
        return $this->suspendu;
    }

    public function setSuspendu(bool $suspendu): static
    {
        $this->suspendu = $suspendu;

        return $this;
    }
}
