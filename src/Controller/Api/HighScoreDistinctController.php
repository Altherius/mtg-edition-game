<?php

namespace App\Controller\Api;

use App\Repository\HighScoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HighScoreDistinctController extends AbstractController
{
    public function __construct(private HighScoreRepository $repository) {}

    public function __invoke(): array
    {
        $data = $this->repository->findGroupedByUser();
        $scores = [];

        foreach ($data as $score) {
            $score[0]->setScore($score['maxScore']);
            $scores[] = $score[0];
        }

        return $scores;
    }
}