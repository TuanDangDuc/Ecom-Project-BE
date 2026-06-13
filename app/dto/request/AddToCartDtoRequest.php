<?php

class AddToCartDtoRequest
{
    public ?int $productVariantId;
    public ?int $quantity;

    public function __construct(array $data)
    {
        $this->productVariantId = isset($data['productVariantId']) ? (int)$data['productVariantId'] : null;
        $this->quantity = isset($data['quantity']) ? (int)$data['quantity'] : 1;
    }

    public function validate(): ?string
    {
        if ($this->productVariantId === null) {
            return "productVariantId is required.";
        }
        if ($this->quantity <= 0) {
            return "Quantity must be greater than zero.";
        }
        return null;
    }
}
