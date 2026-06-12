<?php

interface IUserRepository
{
    function checkExitsByUsername(string $username): bool;
    function checkExitsByEmail(string $email): bool;
    function findByUsername(string $username): ?Users;
    function findByEmail(string $email): ?Users;
    function save(Users $user): bool;
    function updatePasswordByEmail(string $email, string $hashedPassword): bool;
}