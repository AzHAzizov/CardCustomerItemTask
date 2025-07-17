<?php declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Order\Order;

interface OrderRepositoryInterface
{
    public function save(Order $order): void;
    public function find(string $id): ?Order;
}
