<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Product\Product;
use App\Domain\Cart\CartItem as DomainCartItem;
use App\Http\Dto\ProductDto;
use App\Models\Cart;
use App\Models\CartItem as EloquentCartItem;
use App\Repositories\CartRepositoryInterface;
use Exception;

class CartRepository implements CartRepositoryInterface
{
    public function __construct(private readonly Cart $cart) {}

    public function addProduct(Product $product, int $quantity, ?int $cart_id): void
    {

        $cart = $this->getCartById($cart_id);
        if (empty($cart)) {
            if (!empty($cart_id)) {
                throw new Exception('Cart now found');
            }
            $cart = $this->createCart();
        }

        $item = EloquentCartItem::where('product_id', $product->id)
            ->where('cart_id', $cart->id)
            ->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->save();
        } else {
            EloquentCartItem::create([
                'product_id' => $product->id,
                'cart_id' => $cart->id,
                'quantity' => $quantity,
            ]);
        }
    }

    private function createCart(): Cart
    {
        return $this->cart->create();
    }

    public function getCartById(int $id): ?Cart
    {
        return $this->cart->find($id);
    }

    public function removeProduct(int $productId, int $quantity, ?int $cart_id): void
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
        $cartItems = EloquentCartItem::all();

        if ($cartItems->isEmpty()) {
            return [];
        }

        $productIds = $cartItems->pluck('product_id')->unique()->all();

        $products = \App\Models\Product::whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        return $cartItems->map(function ($item) use ($products) {
            $product = $products[$item->product_id] ?? null;

            if (!$product) {
                return null;
            }

            return new \App\Domain\Cart\CartItem(
                new \App\Http\Dto\ProductDto(
                    id: (int) $product->id,
                    name: $product->name ?? '',
                    price: (float) $product->price,
                    quantity: (int) $item->quantity,
                    cart_id: (int) $item->cart_id
                ),
            );
        })->filter()->values()->all(); // фильтруем null, если вдруг не найдется товар
    }
}
