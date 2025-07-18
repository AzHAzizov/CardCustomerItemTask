<?php

namespace App\Domain\Cart;

readonly class Cart
{
    public function __construct(
        public int $id,
        public \DateTimeImmutable $createdAt
    ) {}
}
