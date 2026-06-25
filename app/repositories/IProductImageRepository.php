<?php

interface IProductImageRepository
{
    // function findAllByVariantId(int $productVariantId): array;
    function create(ProductImages $image): bool;
    function delete(int $id): bool;
}
