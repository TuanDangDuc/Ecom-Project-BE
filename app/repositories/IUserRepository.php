<?php

interface IUserRepository
{
    function checkExitsByUsername(string $username): bool;
    function checkExitsByEmail(string $email): bool;
    function findByUsername(string $username): ?Users;
    function save(Users $user): bool;
}