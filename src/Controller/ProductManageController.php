<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Template;
use App\Form\ProductFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ARTIST')]
class ProductManageController extends AbstractController
{
    #[Route('/template/{id}/product/new', name: 'app_product_new')]
    public function new(Template $template, Request $request, EntityManagerInterface $em): Response
    {
        if ($template->getArtwork()->getArtist()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $product = new Product();
        $product->setTemplate($template);

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product added !');
            return $this->redirectToRoute('app_template_manage', ['id' => $template->getArtwork()->getId()]);
        }

        return $this->render('product_manage/new.html.twig', [
            'form' => $form,
            'template' => $template,
        ]);
    }

    #[Route('/product/{id}/delete', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        if ($product->getTemplate()->getArtwork()->getArtist()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $artworkId = $product->getTemplate()->getArtwork()->getId();
            $em->remove($product);
            $em->flush();
            $this->addFlash('success', 'Product deleted.');
            return $this->redirectToRoute('app_template_manage', ['id' => $artworkId]);
        }

        return $this->redirectToRoute('app_dashboard_artist');
    }
}
