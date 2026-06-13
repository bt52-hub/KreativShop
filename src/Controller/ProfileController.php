<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{
    #[Route('/dashboard/customer/edit', name: 'app_dashboard_customer_edit')]
    #[IsGranted('ROLE_CUSTOMER')]
    public function customerEdit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        assert($user instanceof User);

        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Profil mis à jour !');
            return $this->redirectToRoute('app_dashboard_customer');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/dashboard/artist/edit', name: 'app_dashboard_artist_edit')]
    #[IsGranted('ROLE_ARTIST')]
    public function artistEdit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        assert($user instanceof User);

        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $em->refresh($user);
            $this->addFlash('success', 'Profil mis à jour !');
            return $this->redirectToRoute('app_dashboard_artist');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }
}
