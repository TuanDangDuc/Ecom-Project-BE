<?php

class CreateProductDtoRequest
{
    public string $name;
    public ?int $typeId;
    public ?string $thumbnailUrl;
    public ?string $imagesUrl;
    public float $basePrice;
    public ?int $categoryId;
    public ?float $ratingAverage;
    public int $shopId;
    public ?string $description;

    public function __construct(array $data)
    {
        $this->name = trim((string)($data['name'] ?? ''));

        $this->typeId = isset($data['typeId'])
            ? (int)$data['typeId']
            : null;

        $this->thumbnailUrl = $data['thumbnailUrl'] ?? null;

        $this->imagesUrl = $data['imagesUrl'] ?? null;

        $this->basePrice = (float)($data['basePrice'] ?? 0);

        $this->categoryId = isset($data['categoryId'])
            ? (int)$data['categoryId']
            : null;

        $this->ratingAverage = isset($data['ratingAverage'])
            ? (float)$data['ratingAverage']
            : 0;

        $this->shopId = (int)($data['shopId'] ?? 1);

        $this->description = $data['description'] ?? null;
    }
}