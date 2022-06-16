<?php

namespace App\Controller\Api;

use App\Repository\HighScoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HighScoreDistinctController extends AbstractController
{
    public function __construct(private HighScoreRepository $repository) {}

    public function __invoke(): array
    {
        return $this->repository->findHighScoresByUser();
    }
}