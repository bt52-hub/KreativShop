<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\User;
use App\Enum\UserRole;
use App\Repository\ArtworkRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        if ($this->isGranted('ROLE_ARTIST')) {
            return $this->redirectToRoute('app_dashboard_artist');
        }
        return $this->redirectToRoute('app_dashboard_customer');
    }

    #[Route('/dashboard/customer', name: 'app_dashboard_customer')]
    public function customer(OrderRepository $orderRepo): Response
    {
        $user = $this->getUser();
        assert($user instanceof User);

        $orders = $orderRepo->findBy(['user' => $user]);

        return $this->render('dashboard/customer.html.twig', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    #[Route('/dashboard/artist', name: 'app_dashboard_artist')]
    public function artist(
        EntityManagerInterface $em,
        ArtworkRepository $artworkRepo,
        OrderRepository $orderRepo
    ): Response {
        $user = $this->getUser();
        assert($user instanceof User);

        $artist = $em->getRepository(Artist::class)->findOneBy(['user' => $user]);
        $artworks = $artist ? $artworkRepo->findBy(['artist' => $artist]) : [];
        $orders = $orderRepo->findBy(['user' => $user]);

        return $this->render('dashboard/artist.html.twig', [
            'user' => $user,
            'artworks' => $artworks,
            'orders' => $orders,
        ]);
    }

    #[Route('/dashboard/become-artist', name: 'app_become_artist')]
    public function becomeArtist(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        assert($user instanceof User);

        $user->setRole(UserRole::Artist);

        $artist = new Artist();
        $artist->setUser($user);
        $artist->setPseudo($user->getFirstname() . ' ' . $user->getName());
        $em->persist($artist);
        $em->flush();

        $this->addFlash('success', 'Félicitations ! Vous êtes maintenant artiste.');
        return $this->redirectToRoute('app_dashboard_artist');
    }
}
