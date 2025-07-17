<?php declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Product\Product;
use App\Domain\Cart\CartItem;

interface CartRepositoryInterface
{
    public function addProduct(Product $product, int $quantity): void;
    public function removeProduct(string $productId): void;
    /** @return CartItem[] */
    public function allItems(): array;
}
