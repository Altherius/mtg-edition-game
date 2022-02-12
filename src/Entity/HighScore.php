<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\HighScoreRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HighScoreRepository::class)]
#[ApiResource(
    denormalizationContext: ["groups" => ["high-score:write"]],
    normalizationContext: ["groups" => ["high-score:read"]],
    order: ["score" => "desc"]
)]
class HighScore
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["high-score:read"])]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'highScores')]
    #[ORM\JoinColumn(nullable: false)]
    #[Gedmo\Blameable(on: 'create')]
    #[Groups(["high-score:read"])]
    private User $user;

    #[ORM\Column(type: 'integer')]
    #[Groups(["high-score:read", "high-score:write"])]
    private int $score = 0;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'create')]
    #[Groups(["high-score:read"])]
    private DateTimeImmutable $scoredAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getScoredAt(): ?DateTimeImmutable
    {
        return $this->scoredAt;
    }

    public function setScoredAt(DateTimeImmutable $scoredAt): self
    {
        $this->scoredAt = $scoredAt;

        return $this;
    }
}
