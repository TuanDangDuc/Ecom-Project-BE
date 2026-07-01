<?php

class ProductVariants
{
    private ?int $id = null;
    private ?int $stock = null;
    private ?string $options = null;
    private ?float $price = null;
    private ?int $productId = null;

    public function __construct(?int $stock = null, ?string $options = null, ?float $price = null, ?int $productId = null)
    {
        $this->stock = $stock;
        $this->options = $options;
        $this->price = $price;
        $this->productId = $productId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): void
    {
        $this->stock = $stock;
    }

    public function getOptions(): ?string
    {
        return $this->options;
    }

    public function setOptions(?string $options): void
    {
        $this->options = $options;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(?int $productId): void
    {
        $this->productId = $productId;
    }

    public static function fromArray(array $data): self
    {
        $variant = new self();

        $variant->id = (int)$data['id'];
        $variant->stock = (int)$data['stock'];
        $variant->options = $data['options'];
        $variant->price = (float)$data['price'];
        $variant->productId = (int)$data['productId'];

        return $variant;
    }
    
}