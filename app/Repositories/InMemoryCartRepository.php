<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Product\Product;
use App\Domain\Cart\CartItem;

class InMemoryCartRepository implements CartRepositoryInterface
{
    /** @var array<string, CartItem> */
    private array $items = [];

    public function addProduct(Product $product, int $quantity): void
    {
        $this->items[$product->id] = new CartItem($product, $quantity);
    }

    public function removeProduct(string $productId): void
    {
        if (!isset($this->items[$productId])) {
            throw new \DomainException("Product with ID '$productId' not found in cart.");
        }

        unset($this->items[$productId]);
    }

    public function allItems(): array
    {
        return array_values($this->items);
    }
}
