<?php

class CreateVariantDtoRequest
{
    public ?int $productId;
    public ?string $options;
    public ?float $price;
    public ?int $stock;

    public function __construct(array $data)
    {
        $this->productId = isset($data['productId']) ? (int)$data['productId'] : null;
        $this->options = $data['options'] ?? null;
        $this->price = isset($data['price']) ? (float)$data['price'] : null;
        $this->stock = isset($data['stock']) ? (int)$data['stock'] : null;
    }

    public function validate(): ?string
    {
        if ($this->productId === null || $this->productId <= 0) {
            return 'productId is required.';
        }

        if ($this->price === null || $this->price < 0) {
            return 'price is required and must be >= 0.';
        }

        if ($this->stock === null || $this->stock < 0) {
            return 'stock is required and must be >= 0.';
        }

        return null;
    }
}
