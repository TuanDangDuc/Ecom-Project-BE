<?php

interface IProductImageRepository
{
    
    function create(ProductImages $image): bool;
    function delete(int $id): bool;
}
