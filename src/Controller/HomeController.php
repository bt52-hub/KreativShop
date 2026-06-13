<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Repository\ArtworkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        ArtworkRepository $artworkRepo,
        EntityManagerInterface $em
    ): Response {
        // NEWS : 8 artworks les plus récents pour le carrousel
        $newArtworks = $artworkRepo->findBy([], ['created_at' => 'DESC'], 8);

        // DISCOVER : dernier artiste inscrit
        $lastArtist = $em->getRepository(Artist::class)->findOneBy(
            [],
            ['id' => 'DESC']
        );

        // Artworks du dernier artiste pour la section Discover
        $discoverArtworks = $lastArtist
            ? $artworkRepo->findBy(['artist' => $lastArtist], ['created_at' => 'DESC'], 2)
            : [];

        // BESTSELLERS : 9 derniers artworks uploadés
        $bestsellerArtworks = $artworkRepo->findBy([], ['created_at' => 'DESC'], 9);

        return $this->render('home/index.html.twig', [
            'newArtworks' => $newArtworks,
            'lastArtist' => $lastArtist,
            'discoverArtworks' => $discoverArtworks,
            'bestsellerArtworks' => $bestsellerArtworks,
        ]);
    }
}