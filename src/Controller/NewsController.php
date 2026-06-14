<?php

namespace App\Controller;

use App\Repository\ArtworkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NewsController extends AbstractController
{
    #[Route('/news', name: 'app_news')]
    public function index(ArtworkRepository $artworkRepo): Response
    {
        $artworks = $artworkRepo->findBy([], ['created_at' => 'DESC'], 12);

        return $this->render('news/index.html.twig', [
            'artworks' => $artworks,
        ]);
    }
}
