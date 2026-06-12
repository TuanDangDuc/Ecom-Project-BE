<?php

class CheckoutDtoRequest
{
    public string $recipientName;
    public string $recipientPhone;
    public string $note;
    public ?int $shippingAddressId;
    public array $cartItemIds;

    public function __construct(array $data)
    {
        $this->recipientName = trim((string)($data['recipientName'] ?? ''));
        $this->recipientPhone = trim((string)($data['recipientPhone'] ?? ''));
        $this->note = trim((string)($data['note'] ?? ''));
        $this->shippingAddressId = isset($data['shippingAddressId']) ? (int)$data['shippingAddressId'] : null;
        $this->cartItemIds = isset($data['cartItemIds']) && is_array($data['cartItemIds']) ? $data['cartItemIds'] : [];
    }

    public function validate(): ?string
    {
        if (empty($this->recipientName) || empty($this->recipientPhone)) {
            return 'Recipient name and phone are required.';
        }
        return null;
    }
}
