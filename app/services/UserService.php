<?php

class UserService
{
    private IUserRepository $userRepository;

    public function __construct(
        IUserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function getAllUserByPage(int $page, int $limit): array
    {
        
        $users = $this->userRepository->getAllUserByPage($page, $limit);

        $dtoList = array_map(function ($user) {
            return UserMapper::UserToUserDtoResponse($user);
        }, $users);

        return [
            "page"  => $page,
            "limit" => $limit,
            "data"  => $dtoList
        ];
    }
    
    public function getUserByUsername(string $username): ?UserDtoResponse 
    {
        $user = $this->userRepository->getUserByUsername($username);
        if (!$user) {
            return null;
        }
        return UserMapper::UserToUserDtoResponse($user);
    }

    public function deleteUserByUsername(string $username): array {
        $result = $this->userRepository->deleteUserByUsername($username);
        if ($result) 
            return ['success' => true, 'message' => 'delete successful.'];
        else 
            return ['success' => false, 'message' => 'Failed to delete user.'];
    }

    public function updateUser(ModifyUserDtoRequest $request): array {
        $user = $this->userRepository->getUserByUsername($request->username);
        if (!$user) {
            return ['success' => false, 'message' => 'User not found.'];
        }
        $new = UserMapper::modifyUserDtoRequestToUser($request);
        if ($new->getEmail() == null)
            $new->setEmail($user['email'] ?? '');
        if ($new->getFullName() == null)
            $new->setFullName($user['fullName'] ?? $user['full_name'] ?? '');
        if ($new->getSex() == null)
            $new->setSex(isset($user['sex']) && $user['sex'] ? Sex::from($user['sex']) : null);
        if ($new->getDateOfBirth() == null) {
            $dob = $user['dateOfBirth'] ?? $user['date_of_birth'] ?? null;
            $new->setDateOfBirth($dob ? new DateTime($dob) : null);
        }
        if ($new->getAvatarUrl() == null)
            $new->setAvatarUrl($user['avatarUrl'] ?? $user['avatar_url'] ?? '');
            
        $new->setUsername($request->username);
        $new->setUpdatedAt(new DateTime());
    
        $result = $this->userRepository->updateUser($new);
        if ($result) {
            return ['success' => true, 'message' => 'Update successful.'];
        } else {
            return ['success' => false, 'message' => 'Failed to update user.'];
        }
    }

    public function updateAccountStatus(string $username, string $status): array {
        $user = $this->userRepository->getUserByUsername($username);
        if (!$user) {
            return ['success' => false, 'message' => 'User not found.'];
        }
        
        if (!in_array($status, ['ACTIVE', 'INACTIVE'])) {
            return ['success' => false, 'message' => 'Invalid status.'];
        }

        $result = $this->userRepository->updateAccountStatus($username, $status);
        if ($result) {
            return ['success' => true, 'message' => 'Account status updated successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to update account status.'];
        }
    }

    public function updateRole(string $username, string $role): array {
        $user = $this->userRepository->getUserByUsername($username);
        if (!$user) {
            return ['success' => false, 'message' => 'User not found.'];
        }
        
        if (!in_array($role, ['ADMIN', 'SELLER', 'BUYER'])) {
            return ['success' => false, 'message' => 'Invalid role.'];
        }

        $result = $this->userRepository->updateRole($username, $role);
        if ($result) {
            return ['success' => true, 'message' => 'Role updated successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to update role.'];
        }
    }
}
