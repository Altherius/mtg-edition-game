<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SetRepository::class)]
#[ORM\Table(name: '`set`')]
#[ApiResource(
    denormalizationContext: ["groups" => ["set:write"]],
    normalizationContext: ["groups" => ["set:read"]],
    paginationEnabled: false
)]
class Set
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["set:read", "card:read"])]
    private int $id;

    #[ORM\Column(type: 'string', length: 150)]
    #[Assert\NotBlank]
    #[Groups(["set:read", "set:write", "card:read"])]
    private string $name;

    #[ORM\Column(type: 'guid')]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[Groups(["set:read", "card:read"])]
    private string $scryfallUuid;

    #[ORM\OneToMany(mappedBy: 'linkedSet', targetEntity: Card::class, orphanRemoval: true)]
    private Collection $cards;

    #[Pure] public function __construct()
    {
        $this->cards = new ArrayCollection();
    }

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

    /**
     * @return Collection<Card>
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setLinkedSet($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        // set the owning side to null (unless already changed)
        if ($this->cards->removeElement($card) && $card->getLinkedSet() === $this) {
            $card->setLinkedSet(null);
        }

        return $this;
    }
}
