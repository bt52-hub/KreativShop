<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Repository\TemplateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'app_product_show')]
    public function show(Artwork $artwork, TemplateRepository $templateRepo): Response
    {
        $templates = $templateRepo->findBy(['artwork' => $artwork]);

        return $this->render('product/show.html.twig', [
            'artwork' => $artwork,
            'templates' => $templates,
        ]);
    }
}
