<?php declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Customer\Customer;

class InMemoryCustomerRepository implements CustomerRepositoryInterface
{
    /** @var Customer[] */
    private array $customers = [];

    private int $nextId = 1;

    public function findByEmail(string $email): ?Customer
    {
        foreach ($this->customers as $customer) {
            if ($customer->email === $email) {
                return $customer;
            }
        }
        return null;
    }

    public function save(Customer $customer): Customer
    {
        $saved = new Customer(
            id: $this->nextId++,
            name: $customer->name,
            email: $customer->email,
            address: $customer->address
        );

        $this->customers[] = $saved;

        return $saved;
    }
}
