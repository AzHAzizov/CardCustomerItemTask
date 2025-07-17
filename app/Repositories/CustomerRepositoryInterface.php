<?php declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Customer\Customer;

interface CustomerRepositoryInterface
{
    public function findByEmail(string $email): ?Customer;

    public function save(Customer $customer): Customer;
}
