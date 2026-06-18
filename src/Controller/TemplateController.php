<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\Template;
use App\Form\TemplateFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ARTIST')]
class TemplateController extends AbstractController
{
    #[Route('/dashboard/artist/artwork/{id}/template/new', name: 'app_template_new')]
    public function new(Artwork $artwork, Request $request, EntityManagerInterface $em): Response
    {
        if ($artwork->getArtist()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $template = new Template();
        $template->setArtwork($artwork);

        $form = $this->createForm(TemplateFormType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($template);
            $em->flush();

            $this->addFlash('success', 'Template added !');
            return $this->redirectToRoute('app_template_manage', ['id' => $artwork->getId()]);
        }

        return $this->render('template/new.html.twig', [
            'form' => $form,
            'artwork' => $artwork,
        ]);
    }

    #[Route('/dashboard/artist/artwork/{id}/templates', name: 'app_template_manage')]
    public function manage(Artwork $artwork): Response
    {
        if ($artwork->getArtist()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('template/manage.html.twig', [
            'artwork' => $artwork,
        ]);
    }

    #[Route('/template/{id}/edit', name: 'app_template_edit')]
    public function edit(Template $template, Request $request, EntityManagerInterface $em): Response
    {
        if ($template->getArtwork()->getArtist()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(TemplateFormType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Template updated !');
            return $this->redirectToRoute('app_template_manage', ['id' => $template->getArtwork()->getId()]);
        }

        return $this->render('template/edit.html.twig', [
            'form' => $form,
            'template' => $template,
        ]);
    }

    #[Route('/template/{id}/delete', name: 'app_template_delete', methods: ['POST'])]
    public function delete(Template $template, Request $request, EntityManagerInterface $em): Response
    {
        if ($template->getArtwork()->getArtist()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete' . $template->getId(), $request->request->get('_token'))) {
            $artworkId = $template->getArtwork()->getId();
            $em->remove($template);
            $em->flush();
            $this->addFlash('success', 'Template deleted.');
            return $this->redirectToRoute('app_template_manage', ['id' => $artworkId]);
        }

        return $this->redirectToRoute('app_dashboard_artist');
    }
}
