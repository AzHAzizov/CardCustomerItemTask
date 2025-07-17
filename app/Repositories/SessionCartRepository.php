<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Product\Product;
use App\Domain\Cart\CartItem;
use Illuminate\Session\SessionManager;

class SessionCartRepository implements CartRepositoryInterface
{
    private const SESSION_KEY = 'cart_items';

    public function __construct(private SessionManager $session) {}

    public function addProduct(Product $product, int $quantity): void
    {
        $items = $this->getSessionItems();
        $items[$product->id] = new CartItem($product, $quantity);
        $this->saveSessionItems($items);
    }

    public function removeProduct(string $productId): void
    {
        $items = $this->getSessionItems();
        if (!isset($items[$productId])) {
            throw new \DomainException("Product with ID '$productId' not found in cart.");
        }
        unset($items[$productId]);
        $this->saveSessionItems($items);
    }

    public function allItems(): array
    {
        return array_values($this->getSessionItems());
    }

    /** @return array<string, CartItem> */
    private function getSessionItems(): array
    {
        return $this->session->get(self::SESSION_KEY, []);
    }

    /** @param array<string, CartItem> $items */
    private function saveSessionItems(array $items): void
    {
        $this->session->put(self::SESSION_KEY, $items);
    }
}
