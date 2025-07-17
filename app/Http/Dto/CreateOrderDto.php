<?php declare(strict_types=1);

namespace App\Http\Dto;

readonly class CreateOrderDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $address
    ) {}
}
