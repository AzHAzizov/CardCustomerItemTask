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
use App\Repositories\ProductRepository;

class CartController extends Controller
{
    public function __construct(private readonly CartService $service) {}

    public function add(Request $request, ValidatorFactory $validator, ProductRepository $productRepository): JsonResponse
    {
        $validated = $validator->make($request->all(), [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);


        if ($validated->fails()) {
            return response()->json([
                'errors' => $validated->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {            
            $data = $validated->validated();
            $product = $productRepository->getOneById((int)$data['product_id']);
            
            if (!$product) {
                throw new \DomainException('Product not found.');
            }


            $dto = new ProductDto(
                id: (int) $product->id,
                name: $product->name,
                price: (float) $product->price,
                quantity: (int) $data['quantity']
            );

            $this->service->addProduct($dto);
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
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'errors' => $validated->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = $validated->validated();
            $product_id = (int) $data['product_id'];
            $quantity = (int) $data['quantity'];

            $this->service->removeProduct(
                new RemoveProductDto($product_id, $quantity)
            );
        } catch (\DomainException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['message' => 'Cart was updated']);
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
