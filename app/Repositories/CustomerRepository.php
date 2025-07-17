<?php declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Customer\Customer as DomainCustomer;
use App\Models\Customer as EloquentCustomer;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function findByEmail(string $email): ?DomainCustomer
    {
        $model = EloquentCustomer::where('email', $email)->first();

        if (!$model) {
            return null;
        }

        return new DomainCustomer(
            id: (int)$model->id,
            name: $model->name,
            email: $model->email,
            address: $model->address
        );
    }

    public function save(DomainCustomer $customer): DomainCustomer
    {
        $model = EloquentCustomer::create([
            'name' => $customer->name,
            'email' => $customer->email,
            'address' => $customer->address,
        ]);

        return new DomainCustomer(
            id: (int)$model->id,
            name: $model->name,
            email: $model->email,
            address: $model->address
        );
    }
}
