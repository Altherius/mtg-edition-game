<?php

namespace App\Controller\Api;

use App\Entity\Card;
use App\Repository\CardRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CardRandomController extends AbstractController
{
    public function __construct(private CardRepository $repository){}

    public function __invoke(): ?Card
    {
        $card = $this->repository->findRandom();
        return $card;
    }
}