<?php declare(strict_types=1);

namespace App\Domain\Product;

readonly class Product
{
    public function __construct(
        public int $id,
        public string $name,
        public float $price,
        public int $quantity,
    ) {}
}
