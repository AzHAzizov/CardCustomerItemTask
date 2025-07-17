<?php declare(strict_types=1);

namespace App\Domain\Cart;

use App\Domain\Product\Product;

readonly class CartItem
{
    public function __construct(
        public Product $product,
        public int $quantity
    ) {}

    public function totalPrice(): float
    {
        return $this->product->price * $this->quantity;
    }
}
