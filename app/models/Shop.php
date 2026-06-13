<?php

class Shop
{
    private int $id;
    private string $name;
    private ?string $description = null;
    private ShopStatus $status;
    private ?string $avatarUrl = null;
    private float $ratingAverage;

    private int $userId;

    public function __construct() {}

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): void { $this->description = $description; }

    public function getStatus(): ShopStatus { return $this->status; }
    public function setStatus(ShopStatus $status): void { $this->status = $status; }

    public function getAvatarUrl(): ?string { return $this->avatarUrl; }
    public function setAvatarUrl(?string $avatarUrl): void { $this->avatarUrl = $avatarUrl; }

    public function getRatingAverage(): float { return $this->ratingAverage; }
    public function setRatingAverage(float $ratingAverage): void { $this->ratingAverage = $ratingAverage; }

    public function getUserId(): int { return $this->userId; }
    public function setUserId(int $userId): void { $this->userId = $userId; }
}