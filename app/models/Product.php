<?php

class Product
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $description = null;
    private ?float $price = null;
    private ?string $thumbnailUrl = null;
    private ?string $imageUrl = null;
    private ?float $basePrice = null;
    private ?float $ratingAverage = null;

    private ?int $categoryId = null;
    private ?int $shopId = null;
    private ?int $productTypeId = null;

    public function __construct(
        ?string $name = null,
        ?string $description = null,
        ?float $price = null,
        ?string $thumbnailUrl = null,
        ?string $imageUrl = null,
        ?float $basePrice = null,
        ?float $ratingAverage = null,
        ?int $categoryId = null,
        ?int $shopId = null,
        ?int $productTypeId = null
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->thumbnailUrl = $thumbnailUrl;
        $this->imageUrl = $imageUrl;
        $this->basePrice = $basePrice;
        $this->ratingAverage = $ratingAverage;
        $this->categoryId = $categoryId;
        $this->shopId = $shopId;
    }

    
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    
    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    
    public function setThumbnailUrl(?string $thumbnailUrl): void
    {
        $this->thumbnailUrl = $thumbnailUrl;
    }

    public function getThumbnailUrl(): ?string
    {
        return $this->thumbnailUrl;
    }

    
    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    
    public function setBasePrice(?float $basePrice): void
    {
        $this->basePrice = $basePrice;
    }

    public function getBasePrice(): ?float
    {
        return $this->basePrice;
    }

    
    public function setRatingAverage(?float $ratingAverage): void
    {
        $this->ratingAverage = $ratingAverage;
    }

    public function getRatingAverage(): ?float
    {
        return $this->ratingAverage;
    }

    
    public function setDescriptionShort(?string $descriptionShort): void
    {
        $this->descriptionShort = $descriptionShort;
    }

    public function getDescriptionShort(): ?string
    {
        return $this->descriptionShort;
    }

    
    public function setCategoryId(?int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    
    public function setShopId(?int $shopId): void
    {
        $this->shopId = $shopId;
    }

    public function getShopId(): ?int
    {
        return $this->shopId;
    }

    
    public function setProductTypeId(?int $productTypeId): void
    {
        $this->productTypeId = $productTypeId;
    }

    public function getProductTypeId(): ?int
    {
        return $this->productTypeId;
    }
}