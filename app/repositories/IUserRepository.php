<?php

interface IUserRepository
{
    function checkExitsByUsername(string $username): bool;
    function checkExitsByEmail(string $email): bool;
    function save(Users $user): bool;
}