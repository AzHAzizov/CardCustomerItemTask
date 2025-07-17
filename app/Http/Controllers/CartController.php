<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Application\Service\CartService;
use App\Http\Dto\AddProductDto;
use App\Http\Dto\RemoveProductDto;
use Illuminate\Http\Response;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Http\JsonResponse;


class CartController extends Controller
{
    public function __construct(private readonly CartService $service) {}

    public function add(Request $request, ValidatorFactory $validator): JsonResponse
    {
        $validated = $validator->make($request->all(), [
            'id' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'errors' => $validated->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $validated->validated();

        $this->service->addProduct(
            new AddProductDto(
                $data['id'],
                $data['name'],
                (float) $data['price'],
                (int) $data['quantity']
            )
        );

        return response()->json(['message' => 'Product added to cart']);
    }

    public function remove(Request $request, ValidatorFactory $validator): JsonResponse
    {
        $validated = $validator->make($request->all(), [
            'productId' => 'required|string',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'errors' => $validated->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $this->service->removeProduct(
                new RemoveProductDto($validated->validated()['productId'])
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
