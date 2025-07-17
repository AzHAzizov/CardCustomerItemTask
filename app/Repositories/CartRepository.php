<?php declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Product\Product;
use App\Domain\Cart\CartItem as DomainCartItem;
use App\Http\Dto\ProductDto;
use App\Models\CartItem as EloquentCartItem;
use App\Repositories\CartRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    public function addProduct(Product $product, int $quantity): void
    {
        EloquentCartItem::updateOrCreate(
            ['product_id' => $product->id],
            [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
            ]
        );
    }

    public function removeProduct(int $productId): void
    {
        $item = EloquentCartItem::where('product_id', $productId)->first();

        if (!$item) {
            throw new \DomainException("Product with ID '$productId' not found in cart.");
        }

        $item->delete();
    }

    public function allItems(): array
    {
        return EloquentCartItem::all()->map(fn($item) => new DomainCartItem(
            new ProductDto($item->product_id, $item->name, (float)$item->price, $item->quantity),
            (int)$item->quantity
        ))->all();
    }
}
