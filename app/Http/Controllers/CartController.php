<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Factory as ValidatorFactory;
use App\Application\Service\CartService;
use App\Http\Dto\ProductDto;
use App\Http\Dto\RemoveProductDto;

class CartController extends Controller
{
    public function __construct(private readonly CartService $service) {}

    public function add(Request $request, ValidatorFactory $validator): JsonResponse
    {
        $validated = $validator->make($request->all(), [
            'product_id' => 'required|string|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'errors' => $validated->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = $validated->validated();

            $this->service->addProduct(
                new ProductDto(
                    productId: $data['product_id'],
                    quantity: (int) $data['quantity']
                )
            );
        } catch (\DomainException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['message' => 'Product added to cart']);
    }

    public function remove(Request $request, ValidatorFactory $validator): JsonResponse
    {
        $validated = $validator->make($request->all(), [
            'product_id' => 'required|string',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'errors' => $validated->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $this->service->removeProduct(
                new RemoveProductDto($validated->validated()['product_id'])
            );
        } catch (\DomainException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['message' => 'Product removed from cart']);
    }

    public function get(): JsonResponse
    {
        $items = $this->service->listItems();

        $response = array_map(function ($item) {
            return [
                'id' => $item->product->id,
                'name' => $item->product->name,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
                'totalPrice' => $item->totalPrice(),
            ];
        }, $items);

        return response()->json($response);
    }
}
