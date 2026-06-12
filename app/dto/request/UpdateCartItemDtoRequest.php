<?php

class UpdateCartItemDtoRequest
{
    public ?int $quantity;

    public function __construct(array $data)
    {
        $this->quantity = isset($data['quantity']) ? (int)$data['quantity'] : null;
    }

    public function validate(): ?string
    {
        if ($this->quantity === null) {
            return "quantity is required.";
        }
        if ($this->quantity <= 0) {
            return "Quantity must be greater than zero.";
        }
        return null;
    }
}
