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
                'quantity' => $quantity,
            ]
        );
    }

    public function removeProduct(int $productId, int $quantity): void
    {
        $item = EloquentCartItem::where('product_id', $productId)->first();

        if (!$item) {
            throw new \DomainException("Product with ID '$productId' not found in cart.");
        }

        if ($item->quantity <= $quantity) {
            $item->delete();
        } else {
            $item->quantity -= $quantity;
            $item->save();
        }
    }

    public function allItems(): array
    {
        return EloquentCartItem::all()->map(fn($item) => new DomainCartItem(
            new ProductDto($item->product_id, $item->name, (float)$item->price, $item->quantity),
            (int)$item->quantity
        ))->all();
    }
}
