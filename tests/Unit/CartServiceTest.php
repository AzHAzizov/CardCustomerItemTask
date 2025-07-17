<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Application\Service\CartService;
use App\Http\Dto\ProductDto;
use App\Http\Dto\RemoveProductDto;
use App\Repositories\CartRepositoryInterface;
use App\Domain\Product\Product;
use App\Domain\Cart\CartItem;

class CartServiceTest extends TestCase
{
    private CartRepositoryInterface $repo;
    private CartService $service;

    protected function setUp(): void
    {
        $this->repo = $this->createMock(CartRepositoryInterface::class);
        $this->service = new CartService($this->repo);
    }

    public function testAddProduct(): void
    {
        $dto = new ProductDto($id = 1, $name = 'Phone', $price = 999.99, $quantity = 2);

        $this->repo
            ->expects($this->once())
            ->method('addProduct')
            ->willReturnCallback(function ($product, $quantity) use (&$capturedProduct) {
                $capturedProduct = $product;
                return null;
            });

        $this->service->addProduct($dto);

        $this->assertInstanceOf(Product::class, $capturedProduct);
        $this->assertSame($id, $capturedProduct->id);
        $this->assertSame($name, $capturedProduct->name);
        $this->assertSame($price, $capturedProduct->price);
    }

    public function testRemoveProduct(): void
    {
        $dto = new RemoveProductDto($id = 1);

        $this->repo
            ->expects($this->once())
            ->method('removeProduct')
            ->with($id);

        $this->service->removeProduct($dto);
    }

    public function testListItems(): void
    {
        $items = [
            new CartItem(
                $dto1 = new ProductDto($id = 1, $name = 'Phone', $price = 999.99, $quantity = 2),
            ),
            new CartItem(
                $dto2 = new ProductDto($id = 2, $name = 'Car', $price = 10233, $quantity = 1),
            ),
        ];

        $this->repo
            ->method('allItems')
            ->willReturn($items);

        $result = $this->service->listItems();

        $this->assertCount(2, $result);
        $this->assertEquals($dto1->id, $result[0]->product->id);
    }
}
