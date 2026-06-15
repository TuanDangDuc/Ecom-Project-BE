<?php

interface IVariantRepository
{
    function findAllByProductId(int $productId): array;
    function findById(int $id): ?ProductVariants;
    function create(ProductVariants $variant): bool;
    function update(int $id, ProductVariants $variant): bool;
    function delete(int $id): bool;
}
