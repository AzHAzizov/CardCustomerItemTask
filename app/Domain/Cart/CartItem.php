<?php declare(strict_types=1);

namespace App\Domain\Cart;

use App\Http\Dto\ProductDto;

readonly class CartItem
{
    public function __construct(
        public ProductDto $product,
    ) {}

    public function totalPrice(): float
    {
        return $this->product->price * $this->product->quantity;
    }
}
