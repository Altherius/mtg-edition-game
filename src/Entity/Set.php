<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SetRepository::class)]
#[ORM\Table(name: '`set`')]
#[ApiResource(
    denormalizationContext: ["groups" => ["set:write"]],
    normalizationContext: ["groups" => ["set:read"]]
)]
class Set
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["set:read"])]
    private int $id;

    #[ORM\Column(type: 'string', length: 150)]
    #[Assert\NotBlank]
    #[Groups(["set:read", "set:write"])]
    private string $name;

    #[ORM\Column(type: 'guid')]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[Groups(["set:read"])]
    private string $scryfallUuid;

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

    public function getScryfallUuid(): ?string
    {
        return $this->scryfallUuid;
    }

    public function setScryfallUuid(string $scryfallUuid): self
    {
        $this->scryfallUuid = $scryfallUuid;

        return $this;
    }
}
