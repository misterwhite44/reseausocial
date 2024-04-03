<?php

namespace App\Entity;

use App\Repository\PostPhotoRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PostPhotoRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'postPhoto:item']),
        new GetCollection(normalizationContext: ['groups' => 'postPhoto:list'])
    ],
    paginationEnabled: false,
)]
class PostPhoto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['postPhoto:list', 'postPhoto:item'])]

    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['postPhoto:list', 'postPhoto:item'])]

    private ?Post $post_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['postPhoto:list', 'postPhoto:item'])]

    private ?Photo $photo_id = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPhotoId(): ?Photo
    {
        return $this->photo_id;
    }

    public function setPhotoId(?Photo $photo_id): static
    {
        $this->photo_id = $photo_id;

        return $this;
    }
}
