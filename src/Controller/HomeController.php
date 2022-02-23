<?php

namespace App\Controller;

use App\Repository\HighScoreRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/profile', name: 'profile')]
    #[IsGranted('ROLE_USER')]
    public function profile(HighScoreRepository $repository): Response
    {
        $scores = $repository->findBest($this->getUser());
        return $this->render('home/profile.html.twig', [
            'scores' => $scores
        ]);
    }
}
