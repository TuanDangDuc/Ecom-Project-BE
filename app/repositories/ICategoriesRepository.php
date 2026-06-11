<?php

interface ICategoriesRepository
{
    function getAll(): array;
    function checkExistsByName(string $name): bool;
    function create(string $name): bool;
}