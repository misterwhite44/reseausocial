<?php

namespace App\Entity;

use App\Repository\AbonnementRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AbonnementRepository::class)]
#[ApiResource(
        operations: [
            new Get(normalizationContext: ['groups' => 'abonnement:item']),
            new GetCollection(normalizationContext: ['groups' => 'abonnement:list'])
        ],
    paginationEnabled: false,
)]
class Abonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['abonnement:list', 'abonnement:item'])]

    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['abonnement:list', 'abonnement:item'])]
    private ?Compte $suiveur_id = null;


    #[ORM\ManyToOne(inversedBy: 'abonnements')]
    #[Groups(['abonnement:list', 'abonnement:item'])]

    private ?Compte $suivi_personne_id = null;

    #[ORM\ManyToOne]
    #[Groups(['abonnement:list', 'abonnement:item'])]

    private ?Etablissement $suivi_etablissement_id = null;

    #[ORM\ManyToOne]
    #[Groups(['abonnement:list', 'abonnement:item'])]

    private ?Hashtag $suivi_hashtag_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSuiveurId(): ?compte
    {
        return $this->suiveur_id;
    }

    public function setSuiveurId(?compte $suiveur_id): static
    {
        $this->suiveur_id = $suiveur_id;

        return $this;
    }

    public function getSuiviPersonneId(): ?Compte
    {
        return $this->suivi_personne_id;
    }

    public function setSuiviPersonneId(?Compte $suivi_personne_id): static
    {
        $this->suivi_personne_id = $suivi_personne_id;

        return $this;
    }

    public function getSuiviEtablissementId(): ?Etablissement
    {
        return $this->suivi_etablissement_id;
    }

    public function setSuiviEtablissementId(?Etablissement $suivi_etablissement_id): static
    {
        $this->suivi_etablissement_id = $suivi_etablissement_id;

        return $this;
    }

    public function getSuiviHashtagId(): ?Hashtag
    {
        return $this->suivi_hashtag_id;
    }

    public function setSuiviHashtagId(?Hashtag $suivi_hashtag_id): static
    {
        $this->suivi_hashtag_id = $suivi_hashtag_id;

        return $this;
    }
}
