<?php declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Product\Product;
use App\Domain\Cart\CartItem;
use App\Models\Cart;

interface CartRepositoryInterface
{
    public function addProduct(Product $product, int $quantity, ?int $cart_id): void;
    public function removeProduct(int $productId, int $quantity, ?int $cart_id): void;
    /** @return CartItem[] */
    public function allItems(): array;
    // public function save(Cart $cart): void;
}
