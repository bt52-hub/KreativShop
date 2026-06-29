<?php

namespace App\Controller;

use App\Repository\ArtworkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BestsellersController extends AbstractController
{
    #[Route('/bestsellers', name: 'app_bestsellers')]
    public function index(ArtworkRepository $artworkRepo): Response
    {
        $artworks = $artworkRepo->findAll();
        shuffle($artworks);
        $artworks = array_slice($artworks, 0, 12);

        return $this->render('bestsellers/index.html.twig', [
            'artworks' => $artworks,
        ]);
    }
}
