<?php declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;

class ProductRepository {
    public function __construct(public readonly Product $product)
    {}

    public function getOneById(int $id): ?Product {
        return $this->product->find($id);
    }
}