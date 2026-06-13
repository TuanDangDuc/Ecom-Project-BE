<?php

interface IUserRepository
{
    function checkExitsByUsername(string $username): bool;
    function checkExitsByEmail(string $email): bool;
    function findByUsername(string $username): ?Users;
    function findByEmail(string $email): ?Users;
    function save(Users $user): bool;
    function updatePasswordByEmail(string $email, string $hashedPassword): bool;
    function getAllUserByPage(int $page, int $limit): ?array;
    function getUserByUsername(string $username): mixed;
    function deleteUserByUsername(string $username): bool;
    function updateUser(Users $user): bool;
    function updateAccountStatus(string $username, string $status): bool;
    function updateRole(string $username, string $role): bool;
}