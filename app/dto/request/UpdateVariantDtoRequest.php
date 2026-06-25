<?php

class UpdateVariantDtoRequest
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
        if ($this->price !== null && $this->price < 0) {
            return 'price must be >= 0.';
        }

        if ($this->stock !== null && $this->stock < 0) {
            return 'stock must be >= 0.';
        }

        return null;
    }
}
