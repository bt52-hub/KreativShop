<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\User;
use App\Enum\OrderStatus;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_CUSTOMER')]
class CheckoutController extends AbstractController
{
    public function __construct(private CartService $cartService) {}

    #[Route('/checkout', name: 'app_checkout')]
    public function index(): Response
    {
        $user = $this->getUser();
        assert($user instanceof User);

        $items = $this->cartService->getFullCart();

        if (empty($items)) {
            $this->addFlash('error', 'Your basket is empty.');
            return $this->redirectToRoute('app_cart');
        }

        return $this->render('checkout/index.html.twig', [
            'items' => $items,
            'total' => $this->cartService->getTotal(),
            'user' => $user,
        ]);
    }

    #[Route('/checkout/confirm', name: 'app_checkout_confirm', methods: ['POST'])]
    public function confirm(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        assert($user instanceof User);

        $items = $this->cartService->getFullCart();

        if (empty($items)) {
            $this->addFlash('error', 'Your basket is empty.');
            return $this->redirectToRoute('app_cart');
        }

        $user = $this->getUser();

        if (!$user->getAddress()) {
            $this->addFlash(
                'error',
                'Veuillez renseigner une adresse de livraison avant de commander.'
            );

            return $this->redirectToRoute('app_profile_edit');
        }

        // Création de la commande
        $order = new Order();
        $order->setUser($user);
        $order->setStatus(OrderStatus::Pending);
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setTotalAmount($this->cartService->getTotal());
        $em->persist($order);

        // Création des lignes de commande
        foreach ($items as $item) {
            $orderItem = new OrderItem();
            $orderItem->setOrderRef($order);
            $orderItem->setProduct($item['product']);
            $orderItem->setQuantity($item['quantity']);
            $orderItem->setPriceAtPurchase($item['product']->getFinalPrice());
            $em->persist($orderItem);
        }

        $em->flush();

        // Vide le panier
        $this->cartService->clear();

        $this->addFlash('success', 'Your order has been confirmed !');
        return $this->redirectToRoute('app_order_confirmation', ['id' => $order->getId()]);
    }
}
