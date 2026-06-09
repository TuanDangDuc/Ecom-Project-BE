<?php

class UserMapper
{
    public static function RegisterDtoRequestToUsers(RegisterDtoRequest $request): Users
    {
        $user = new Users();
        $user->setUsername($request->username);
        $user->setPassword(password_hash($request->password, PASSWORD_BCRYPT));
        $user->setEmail($request->email);
        $user->setRole(Role::BUYER);
        $user->setAccountStatus(AccountStatus::ACTIVE);
        return $user;
    }
}
