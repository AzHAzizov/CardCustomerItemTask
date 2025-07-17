<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Repositories\CartRepositoryInterface;
use App\Domain\Product\Product;
use App\Http\Dto\AddProductDto;
use App\Http\Dto\RemoveProductDto;

readonly class CartService
{
    public function __construct(private CartRepositoryInterface $repository) {}

    public function addProduct(AddProductDto $addProduct): void
    {
        $product = new Product($addProduct->id, $addProduct->name, $addProduct->price);
        $this->repository->addProduct($product, $addProduct->quantity);
    }

    public function removeProduct(RemoveProductDto $dto): void
    {
        $this->repository->removeProduct($dto->productId);
    }

    public function listItems(): array
    {
        return $this->repository->allItems();
    }
}
