<?php declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Order\Order;

class InMemoryOrderRepository implements OrderRepositoryInterface
{
    /** @var array<string, Order> */
    private array $orders = [];

    public function save(Order $order): void
    {
        $this->orders[$order->id] = $order;
    }

    public function find(string $id): ?Order
    {
        return $this->orders[$id] ?? null;
    }
}
