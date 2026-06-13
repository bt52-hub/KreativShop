<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\Artist;
use App\Form\ArtworkFormType;
use App\Repository\ArtworkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard/artist/artwork')]
#[IsGranted('ROLE_ARTIST')]
class ArtworkController extends AbstractController
{
    #[Route('/', name: 'app_artwork_index')]
    public function index(ArtworkRepository $repo, EntityManagerInterface $em): Response
    {
        $artist = $em->getRepository(Artist::class)->findOneBy(['user' => $this->getUser()]);
        $artworks = $repo->findBy(['artist' => $artist]);

        return $this->render('artwork/index.html.twig', [
            'artworks' => $artworks,
        ]);
    }

    #[Route('/new', name: 'app_artwork_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $artist = $em->getRepository(Artist::class)->findOneBy(['user' => $this->getUser()]);
        // dd($artist, $this->getUser());
        $artwork = new Artwork();
        $form = $this->createForm(ArtworkFormType::class, $artwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $artwork->setArtist($artist);
            $artwork->setCreatedAt(new \DateTimeImmutable());
            $em->persist($artwork);
            $em->flush();

            $this->addFlash('success', 'Artwork créé avec succès !');
            return $this->redirectToRoute('app_dashboard_artist');
        }

        return $this->render('artwork/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_artwork_edit')]
    public function edit(Artwork $artwork, Request $request, EntityManagerInterface $em): Response
    {
        if ($artwork->getArtist()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(ArtworkFormType::class, $artwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Artwork modifié avec succès !');
            return $this->redirectToRoute('app_dashboard_artist');
        }

        return $this->render('artwork/edit.html.twig', [
            'form' => $form,
            'artwork' => $artwork,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_artwork_delete', methods: ['POST'])]
    public function delete(Artwork $artwork, Request $request, EntityManagerInterface $em): Response
    {
        if ($artwork->getArtist()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete' . $artwork->getId(), $request->request->get('_token'))) {
            $em->remove($artwork);
            $em->flush();
            $this->addFlash('success', 'Artwork supprimé.');
        }

        return $this->redirectToRoute('app_dashboard_artist');
    }
}
