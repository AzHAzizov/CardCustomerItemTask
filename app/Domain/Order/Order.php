<?php declare(strict_types=1);

namespace App\Domain\Order;

use App\Domain\Cart\CartItem;

readonly class Order
{
    public function __construct(
        public string $id,
        public int $customerId,
        public string $address,
        /** @var CartItem[] */
        public array $items,
        public \DateTimeImmutable $createdAt
    ) {}

    public function totalAmount(): float
    {
        return array_reduce($this->items, fn($sum, CartItem $item) => $sum + $item->totalPrice(), 0.0);
    }
}
