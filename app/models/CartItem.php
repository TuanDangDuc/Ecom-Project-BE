<?php

class CartItem
{
    private ?int $id = null;
    private ?int $cartId = null;
    private ?int $quantity = null;
    private ?float $priceAtAdded = null;
    private ?int $productVariantId = null;
    private ?float $currentPrice = null;
    private ?int $currentStock = null;
    private ?array $variantOptions = null;
    private ?int $productId = null;
    private ?string $productName = null;
    private ?string $productThumbnail = null;

    public function __construct(array $data = [])
    {
        if (array_key_exists('id', $data)) {
            $this->id = $data['id'];
        }
        if (array_key_exists('cartId', $data)) {
            $this->cartId = $data['cartId'];
        }
        if (array_key_exists('quantity', $data)) {
            $this->quantity = $data['quantity'];
        }
        if (array_key_exists('priceAtAdded', $data)) {
            $this->priceAtAdded = $data['priceAtAdded'];
        }
        if (array_key_exists('productVariantId', $data)) {
            $this->productVariantId = $data['productVariantId'];
        }
        if (array_key_exists('currentPrice', $data)) {
            $this->currentPrice = $data['currentPrice'];
        }
        if (array_key_exists('currentStock', $data)) {
            $this->currentStock = $data['currentStock'];
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

    public function getCartId(): ?int
    {
        return $this->cartId;
    }

    public function setCartId(?int $cartId): void
    {
        $this->cartId = $cartId;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getPriceAtAdded(): ?float
    {
        return $this->priceAtAdded;
    }

    public function setPriceAtAdded(?float $priceAtAdded): void
    {
        $this->priceAtAdded = $priceAtAdded;
    }

    public function getProductVariantId(): ?int
    {
        return $this->productVariantId;
    }

    public function setProductVariantId(?int $productVariantId): void
    {
        $this->productVariantId = $productVariantId;
    }

    public function getCurrentPrice(): ?float
    {
        return $this->currentPrice;
    }

    public function setCurrentPrice(?float $currentPrice): void
    {
        $this->currentPrice = $currentPrice;
    }

    public function getCurrentStock(): ?int
    {
        return $this->currentStock;
    }

    public function setCurrentStock(?int $currentStock): void
    {
        $this->currentStock = $currentStock;
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
            'cartId' => $this->cartId,
            'quantity' => $this->quantity,
            'priceAtAdded' => $this->priceAtAdded,
            'productVariantId' => $this->productVariantId,
            'currentPrice' => $this->currentPrice,
            'currentStock' => $this->currentStock,
            'variantOptions' => $this->variantOptions,
            'productId' => $this->productId,
            'productName' => $this->productName,
            'productThumbnail' => $this->productThumbnail,
        ];
    }
}