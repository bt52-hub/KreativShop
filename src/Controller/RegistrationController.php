<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $hasher->hashPassword($user, $form->get('plainPassword')->getData())
            );

            $role = $form->get('role')->getData();
            $user->setRole($role);
            $user->setCreatedAt(new \DateTimeImmutable());

            $em->persist($user);

            if ($role->value === 'artist') {
                $artist = new Artist();
                $artist->setUser($user);
                $artist->setPseudo($user->getFirstname() . ' ' . $user->getName());
                $em->persist($artist);
            }

            $em->flush();

            $this->addFlash('success', 'Compte créé avec succès ! Connectez-vous.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form,
        ]);
    }
}
