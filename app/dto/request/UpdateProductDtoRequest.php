<?php

class UpdateProductDtoRequest
{
    public ?string $name;
    public ?int $typeId;
    public ?string $thumbnailUrl;
    public ?string $imagesUrl;
    public ?float $basePrice;
    public ?int $categoryId;
    public ?float $ratingAverage;
    public ?int $shopId;
    public ?string $description;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? null;

        $this->typeId = isset($data['typeId'])
            ? (int)$data['typeId']
            : null;

        $this->thumbnailUrl = $data['thumbnailUrl'] ?? null;

        $this->imagesUrl = $data['imagesUrl'] ?? null;

        $this->basePrice = isset($data['basePrice'])
            ? (float)$data['basePrice']
            : null;

        $this->categoryId = isset($data['categoryId'])
            ? (int)$data['categoryId']
            : null;

        $this->ratingAverage = isset($data['ratingAverage'])
            ? (float)$data['ratingAverage']
            : null;

        $this->shopId = isset($data['shopId'])
            ? (int)$data['shopId']
            : null;

        $this->description = $data['description'] ?? null;
    }
}