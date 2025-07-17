<?php declare(strict_types=1);

namespace App\Http\Dto;

readonly class ProductDto
{
     public function __construct(
        public int $id,
        public string $name,
        public float $price,
        public int $quantity,
    ) {}
}
