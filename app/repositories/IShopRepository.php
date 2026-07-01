<?php

interface IShopRepository {
    public function save(Shop $shop): bool;
    public function findById(int $id): ?Shop;
    public function updateShop(Shop $shop): bool;
    public function findByUserId(int $userId): array;
    public function updateShopStatus(int $id, string $status): bool;
    public function updateShopRating(int $id, float $rating): bool;
    public function deleteShop(int $id): bool;
    public function findAll(): array;
}