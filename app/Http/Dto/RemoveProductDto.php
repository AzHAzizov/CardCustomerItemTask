<?php

declare(strict_types=1);

namespace App\Http\Dto;

readonly class RemoveProductDto
{
    public function __construct(
        public int $productId,
        public int $quantity,
        public ?int $cart_id
    ) {}
}
