<?php

class OrderItem
{
    private ?int $id = null;
    private ?int $orderId = null;
    private ?int $quantity = null;
    private ?float $priceAtPurchase = null;
    private ?string $orderStatus = null;
    private ?string $trackingNumber = null;
    private ?string $shippingProvider = null;
    private ?int $productVariantId = null;
    private ?array $variantOptions = null;
    private ?int $productId = null;
    private ?string $productName = null;
    private ?string $productThumbnail = null;

    public function __construct(array $data = [])
    {
        if (array_key_exists('id', $data)) {
            $this->id = $data['id'];
        }
        if (array_key_exists('orderId', $data)) {
            $this->orderId = $data['orderId'];
        }
        if (array_key_exists('quantity', $data)) {
            $this->quantity = $data['quantity'];
        }
        if (array_key_exists('priceAtPurchase', $data)) {
            $this->priceAtPurchase = $data['priceAtPurchase'];
        }
        if (array_key_exists('orderStatus', $data)) {
            $this->orderStatus = $data['orderStatus'];
        }
        if (array_key_exists('trackingNumber', $data)) {
            $this->trackingNumber = $data['trackingNumber'];
        }
        if (array_key_exists('shippingProvider', $data)) {
            $this->shippingProvider = $data['shippingProvider'];
        }
        if (array_key_exists('productVariantId', $data)) {
            $this->productVariantId = $data['productVariantId'];
        }
        if (array_key_exists('variantOptions', $data)) {
            $this->variantOptions = $data['variantOptions'];
        }
        if (array_key_exists('productId', $data)) {
            $this->productId = $data['productId'];
        }
        if (array_key_exists('productName', $data)) {
            $this->productName = $data['productName'];
        }
        if (array_key_exists('productThumbnail', $data)) {
            $this->productThumbnail = $data['productThumbnail'];
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

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function setOrderId(?int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getPriceAtPurchase(): ?float
    {
        return $this->priceAtPurchase;
    }

    public function setPriceAtPurchase(?float $priceAtPurchase): void
    {
        $this->priceAtPurchase = $priceAtPurchase;
    }

    public function getOrderStatus(): ?string
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(?string $orderStatus): void
    {
        $this->orderStatus = $orderStatus;
    }

    public function getTrackingNumber(): ?string
    {
        return $this->trackingNumber;
    }

    public function setTrackingNumber(?string $trackingNumber): void
    {
        $this->trackingNumber = $trackingNumber;
    }

    public function getShippingProvider(): ?string
    {
        return $this->shippingProvider;
    }

    public function setShippingProvider(?string $shippingProvider): void
    {
        $this->shippingProvider = $shippingProvider;
    }

    public function getProductVariantId(): ?int
    {
        return $this->productVariantId;
    }

    public function setProductVariantId(?int $productVariantId): void
    {
        $this->productVariantId = $productVariantId;
    }

    public function getVariantOptions(): ?array
    {
        return $this->variantOptions;
    }

    public function setVariantOptions(?array $variantOptions): void
    {
        $this->variantOptions = $variantOptions;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(?int $productId): void
    {
        $this->productId = $productId;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(?string $productName): void
    {
        $this->productName = $productName;
    }

    public function getProductThumbnail(): ?string
    {
        return $this->productThumbnail;
    }

    public function setProductThumbnail(?string $productThumbnail): void
    {
        $this->productThumbnail = $productThumbnail;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'orderId' => $this->orderId,
            'quantity' => $this->quantity,
            'priceAtPurchase' => $this->priceAtPurchase,
            'orderStatus' => $this->orderStatus,
            'trackingNumber' => $this->trackingNumber,
            'shippingProvider' => $this->shippingProvider,
            'productVariantId' => $this->productVariantId,
            'variantOptions' => $this->variantOptions,
            'productId' => $this->productId,
            'productName' => $this->productName,
            'productThumbnail' => $this->productThumbnail,
        ];
    }
}