<?php

interface IProductRepository {
    function findAll(array $filters = []): array;
    function findById(int $id): ?array;
    function showShopProduct(int $shopId): array;
    function create(Product $product): bool;
    function update(int $id, Product $product): bool;
    function delete(int $id): bool;
}