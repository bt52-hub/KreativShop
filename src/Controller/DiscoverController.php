<?php

namespace App\Controller;

use App\Entity\Artist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DiscoverController extends AbstractController
{
    #[Route('/discover', name: 'app_discover')]
    public function index(EntityManagerInterface $em): Response
    {
        $artists = $em->getRepository(Artist::class)->findAll();

        return $this->render('discover/index.html.twig', [
            'artists' => $artists,
        ]);
    }
}
