<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Repository\ArtworkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArtistController extends AbstractController
{
    #[Route('/artist/{id}', name: 'app_artist_show')]
    public function show(
        Artist $artist,
        ArtworkRepository $artworkRepo
    ): Response {
        $artworks = $artworkRepo->findBy(
            ['artist' => $artist],
            ['created_at' => 'DESC']
        );

        return $this->render('artist/show.html.twig', [
            'artist' => $artist,
            'artworks' => $artworks,
        ]);
    }
}
