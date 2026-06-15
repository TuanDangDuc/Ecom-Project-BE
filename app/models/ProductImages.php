<?php

class ProductImages
{
    private ?int $id;
    private string $url;
    private ?int $imageOrder;
    private int $productVariantId;

    public function __construct(?int $id = null, string $url = "", ?int $imageOrder = null, int $productVariantId = 1)
    {
        $this->url = $url;
        $this->imageOrder = $imageOrder;
        $this->productVariantId = $productVariantId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getImageOrder(): ?int
    {
        return $this->imageOrder;
    }

    public function setImageOrder(?int $imageOrder): void
    {
        $this->imageOrder = $imageOrder;
    }

    public function getProductVariantId(): int
    {
        return $this->productVariantId;
    }

    public function setProductVariantId(int $productVariantId): void
    {
        $this->productVariantId = $productVariantId;
    }

}