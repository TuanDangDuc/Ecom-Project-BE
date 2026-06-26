<?php

interface ICategoriesRepository
{
    function getAll(): array;
    function checkExistsByName(string $name): bool;
    function create(string $name): bool;
    function update(int $id, string $name): bool;
    function delete(int $id): bool;
}