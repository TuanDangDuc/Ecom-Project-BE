<?php

interface IProductTypesRepository
{
    function getAll(): array;
    function checkExistsByName(string $name): bool;
    function create(ProductType $productType): bool;
    function update(int $id, ProductType $productType): bool;
    function delete(int $id): bool;
}