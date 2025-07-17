<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Http\Response;
use App\Http\Dto\CreateOrderDto;
use App\Application\Service\CreateOrderService;

class OrderController extends Controller
{
    public function __construct(private readonly CreateOrderService $service) {}

    public function create(Request $request, ValidatorFactory $validator)
    {
        $validated = $validator->make($request->all(), [
            'name' => 'required|string|min:2',
            'email' => 'required|email',
            'address' => 'required|string|min:5',
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $dto = new CreateOrderDto(
                name: $validated->validated()['name'],
                email: $validated->validated()['email'],
                address: $validated->validated()['address']
            );

            $order = $this->service->create($dto);
        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'id' => $order->id,
            'address' => $order->address,
            'createdAt' => $order->createdAt->format('c'),
            'items' => array_map(fn($item) => [
                'id' => $item->product->id,
                'name' => $item->product->name,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
                'totalPrice' => $item->totalPrice(),
            ], $order->items),
            'total' => $order->totalAmount()
        ]);
    }
}
