<?php

interface IProductTypesRepository
{
    function getAll(): array;
    function checkExistsByName(string $name): bool;
    function create(ProductType $productType): bool;
}