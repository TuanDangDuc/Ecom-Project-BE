<?php

class Orders
{
    private ?int $id = null;
    private ?string $orderCode = null;
    private ?int $userId = null;
    private ?string $recipientName = null;
    private ?string $recipientPhone = null;
    private ?string $note = null;
    private ?float $subtotal = null;
    private ?float $shippingFee = null;
    private ?float $totalAmount = null;
    private ?int $shippingAddressId = null;
    private ?string $canceledAt = null;
    private ?string $createdAt = null;

    public function __construct(array $data = [])
    {
        if (array_key_exists('id', $data)) {
            $this->id = $data['id'];
        }
        if (array_key_exists('orderCode', $data)) {
            $this->orderCode = $data['orderCode'];
        }
        if (array_key_exists('userId', $data)) {
            $this->userId = $data['userId'];
        }
        if (array_key_exists('recipientName', $data)) {
            $this->recipientName = $data['recipientName'];
        }
        if (array_key_exists('recipientPhone', $data)) {
            $this->recipientPhone = $data['recipientPhone'];
        }
        if (array_key_exists('note', $data)) {
            $this->note = $data['note'];
        }
        if (array_key_exists('subtotal', $data)) {
            $this->subtotal = $data['subtotal'];
        }
        if (array_key_exists('shippingFee', $data)) {
            $this->shippingFee = $data['shippingFee'];
        }
        if (array_key_exists('totalAmount', $data)) {
            $this->totalAmount = $data['totalAmount'];
        }
        if (array_key_exists('shippingAddressId', $data)) {
            $this->shippingAddressId = $data['shippingAddressId'];
        }
        if (array_key_exists('canceledAt', $data)) {
            $this->canceledAt = $data['canceledAt'];
        }
        if (array_key_exists('createdAt', $data)) {
            $this->createdAt = $data['createdAt'];
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getOrderCode(): ?string
    {
        return $this->orderCode;
    }

    public function setOrderCode(?string $orderCode): void
    {
        $this->orderCode = $orderCode;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    public function getRecipientName(): ?string
    {
        return $this->recipientName;
    }

    public function setRecipientName(?string $recipientName): void
    {
        $this->recipientName = $recipientName;
    }

    public function getRecipientPhone(): ?string
    {
        return $this->recipientPhone;
    }

    public function setRecipientPhone(?string $recipientPhone): void
    {
        $this->recipientPhone = $recipientPhone;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): void
    {
        $this->note = $note;
    }

    public function getSubtotal(): ?float
    {
        return $this->subtotal;
    }

    public function setSubtotal(?float $subtotal): void
    {
        $this->subtotal = $subtotal;
    }

    public function getShippingFee(): ?float
    {
        return $this->shippingFee;
    }

    public function setShippingFee(?float $shippingFee): void
    {
        $this->shippingFee = $shippingFee;
    }

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(?float $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function getShippingAddressId(): ?int
    {
        return $this->shippingAddressId;
    }

    public function setShippingAddressId(?int $shippingAddressId): void
    {
        $this->shippingAddressId = $shippingAddressId;
    }

    public function getCanceledAt(): ?string
    {
        return $this->canceledAt;
    }

    public function setCanceledAt(?string $canceledAt): void
    {
        $this->canceledAt = $canceledAt;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'orderCode' => $this->orderCode,
            'userId' => $this->userId,
            'recipientName' => $this->recipientName,
            'recipientPhone' => $this->recipientPhone,
            'note' => $this->note,
            'subtotal' => $this->subtotal,
            'shippingFee' => $this->shippingFee,
            'totalAmount' => $this->totalAmount,
            'shippingAddressId' => $this->shippingAddressId,
            'canceledAt' => $this->canceledAt,
            'createdAt' => $this->createdAt,
        ];
    }
}