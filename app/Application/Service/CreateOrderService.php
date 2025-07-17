<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Customer\Customer;
use App\Domain\Order\Order;
use App\Http\Dto\CreateOrderDto;
use App\Repositories\CartRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\CustomerRepositoryInterface;

readonly class CreateOrderService
{
    public function __construct(
        private CartRepositoryInterface $cart,
        private OrderRepositoryInterface $orders,
        private CustomerRepositoryInterface $customers
    ) {}

    public function create(CreateOrderDto $dto): Order
    {
        $customer = $this->customers->findByEmail($dto->email);

        if (!$customer) {
            $customer = new Customer(
                id: 0,
                name: $dto->name,
                email: $dto->email,
                address: $dto->address
            );

            $customer = $this->customers->save($customer);
        }

        $items = $this->cart->allItems();

        if (empty($items)) {
            throw new \DomainException('Cart is empty.');
        }

        $order = new Order(
            id: uniqid('order_', true),
            customerId: $customer->id,
            address: $customer->address,
            items: $items,
            createdAt: new \DateTimeImmutable()
        );

        $this->orders->save($order);

        return $order;
    }
}
