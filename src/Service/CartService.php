<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepository
    ) {}

    private function getSession()
    {
        return $this->requestStack->getSession();
    }

    // Récupère le panier brut (tableau id => quantité)
    public function getCart(): array
    {
        return $this->getSession()->get('cart', []);
    }

    // Ajoute un produit au panier
    public function add(int $productId): void
    {
        $cart = $this->getCart();
        $cart[$productId] = ($cart[$productId] ?? 0) + 1;
        $this->getSession()->set('cart', $cart);
    }

    // Retire une unité d'un produit
    public function remove(int $productId): void
    {
        $cart = $this->getCart();
        if (isset($cart[$productId])) {
            $cart[$productId]--;
            if ($cart[$productId] <= 0) {
                unset($cart[$productId]);
            }
        }
        $this->getSession()->set('cart', $cart);
    }

    // Supprime complètement un produit
    public function delete(int $productId): void
    {
        $cart = $this->getCart();
        unset($cart[$productId]);
        $this->getSession()->set('cart', $cart);
    }

    // Vide le panier
    public function clear(): void
    {
        $this->getSession()->remove('cart');
    }

    // Retourne le panier enrichi avec les objets Product
    public function getFullCart(): array
    {
        $cart = $this->getCart();
        $fullCart = [];

        foreach ($cart as $productId => $quantity) {
            $product = $this->productRepository->find($productId);
            if ($product) {
                $fullCart[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->getFinalPrice() * $quantity,
                ];
            }
        }

        return $fullCart;
    }

    // Calcule le total du panier
    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getFullCart() as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }

    // Nombre total d'articles
    public function getCount(): int
    {
        return array_sum($this->getCart());
    }
}
