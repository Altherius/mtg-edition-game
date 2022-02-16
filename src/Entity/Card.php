<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CardRepository;
use App\Controller\Api\CardRandomController;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CardRepository::class)]
#[ApiResource(
    itemOperations: [
        'random' => [
            'method' => 'get',
            'path' => '/cards/random',
            'controller' => CardRandomController::class,
            'read' => false,
        ],
        'get',
        'put',
        'patch',
        'delete',
    ],
    denormalizationContext: ["groups" => "card:write"],
    normalizationContext: ["groups" => "card:read"]
)]
class Card
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["card:read"])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["card:read"])]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["card:read"])]
    private string $image;

    #[ORM\ManyToOne(targetEntity: Set::class, inversedBy: 'cards')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["card:read"])]
    private Set $linkedSet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getLinkedSet(): ?Set
    {
        return $this->linkedSet;
    }

    public function setLinkedSet(?Set $linkedSet): self
    {
        $this->linkedSet = $linkedSet;

        return $this;
    }
}
