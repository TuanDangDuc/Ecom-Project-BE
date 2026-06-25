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

        public static function UserToUserDtoResponse(array $user): ?UserDtoResponse
    {
        $dto = new UserDtoResponse();

        $dto->setId($user['id'] ?? 0);
        $dto->setUsername($user['username'] ?? '');
        $dto->setEmail($user['email'] ?? '');
        $dto->setFullName($user['fullName'] ?? $user['full_name'] ?? '');
        $dto->setSex($user['sex'] ?? '');
        $dto->setRole($user['role'] ?? '');
        
        $dto->setDateOfBirth($user['dateOfBirth'] ?? null);
        
        $dto->setAvatarUrl($user['avatarUrl'] ?? '');
        $dto->setAccountStatus($user['accountStatus'] ?? '');
        
        $dto->setCreatedAt($user['createAt'] ?? $user['createdAt'] ?? null);
        $dto->setUpdatedAt($user['updateAt'] ?? $user['updatedAt'] ?? null);

        return $dto;
    }

    public static function modifyUserDtoRequestToUser(ModifyUserDtoRequest $request): Users {
        $sex = $request->sex == 'MALE'
            ? Sex::MALE
            : ($request->sex == 'FEMALE' ? Sex::FEMALE : Sex::OTHER);

        $user = new Users();
        $user->setEmail($request->email);
        $user->setFullName($request->fullName);
        $user->setSex($sex);

        if ($request->dateOfBirth instanceof DateTime) {
            $user->setDateOfBirth($request->dateOfBirth);
        } elseif (!empty($request->dateOfBirth)) {
            $user->setDateOfBirth(new DateTime($request->dateOfBirth));
        }

        $user->setAvatarUrl($request->avatarUrl ?? '');

        return $user;
    }


}
