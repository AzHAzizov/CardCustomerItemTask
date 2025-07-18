<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Repositories\CartRepositoryInterface;
use App\Domain\Product\Product;
use App\Http\Dto\ProductDto;
use App\Http\Dto\RemoveProductDto;
use App\Models\Cart;

readonly class CartService
{
    public function __construct(private CartRepositoryInterface $repository) {}

    public function addProduct(ProductDto $productDto): void
    {
        $product = new Product($productDto->id, $productDto->name, $productDto->price, $productDto->quantity);
        $this->repository->addProduct($product, $productDto->quantity, $productDto->cart_id);
    }

    public function removeProduct(RemoveProductDto $dto): void
    {
        $this->repository->removeProduct($dto->productId, $dto->quantity, $dto->cart_id);
    }

    public function listItems(): array
    {
        return $this->repository->allItems();
    }

    public function createCart(): int
    {
        $cart = new Cart();
        $this->repository->save($cart);

        return $cart->id;
    }
}
