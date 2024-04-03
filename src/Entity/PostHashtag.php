<?php

namespace App\Entity;

use App\Repository\PostHashtagRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PostHashtagRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'postHashtag:item']),
        new GetCollection(normalizationContext: ['groups' => 'postHashtag:list'])
    ],
    paginationEnabled: false,
)]
class PostHashtag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['postHashtag:list', 'postHashtag:item'])]

    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['postHashtag:list', 'postHashtag:item'])]

    private ?Post $post_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['postHashtag:list', 'postHashtag:item'])]

    private ?Hashtag $hashtag_id = null;

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

    public function getHashtagId(): ?Hashtag
    {
        return $this->hashtag_id;
    }

    public function setHashtagId(?Hashtag $hashtag_id): static
    {
        $this->hashtag_id = $hashtag_id;

        return $this;
    }
}
