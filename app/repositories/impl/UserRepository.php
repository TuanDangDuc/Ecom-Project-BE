<?php
class UserRepository implements IUserRepository
{
    public function __construct(
        private PDO $db
    ){}

    public function checkExitsByUsername(string $username): bool
    {
        $sql = "select * from users where username = ?";

        $statement = $this->db->prepare($sql);
        $statement->execute([$username]);
        
        return $statement->fetch() !== false;
    }

    public function checkExitsByEmail(string $email): bool
    {
        $sql = "select * from users where email = ?";

        $statement = $this->db->prepare($sql);
        $statement->execute([$email]);
        
        return $statement->fetch() !== false;
    }

    public function save(Users $user): bool
    {
        $sql = "insert into users (username, email, password, role, accountStatus) 
                values (?, ?, ?, ?, ?)";

        $statement = $this->db->prepare($sql);

        $roleValue = $user->getRole() !== null ? $user->getRole()->value : null;
        $statusValue = $user->getAccountStatus() !== null ? $user->getAccountStatus()->value : null;

        return $statement->execute([
            $user->getUsername(),
            $user->getEmail(),
            $user->getPassword(),
            $roleValue,
            $statusValue
        ]);
    }

    public function findByUsername(string $username): ?Users
    {
        $sql = "select * from users where username = ?";

        $statement = $this->db->prepare($sql);
        $statement->execute([$username]);
        
        $data = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $user = new Users();
        $user->setId((int)$data['id']);
        $user->setUsername($data['username']);
        $user->setPassword($data['password']);
        $user->setEmail($data['email']);
        $user->setRole(Role::from($data['role'] ?? 'BUYER'));
        $user->setAccountStatus(AccountStatus::from($data['accountStatus'] ?? 'ACTIVE'));

        if (!empty($data['fullName'])) {
            $user->setFullName($data['fullName']);
        }

        if (!empty($data['sex'])) {
            $user->setSex(Sex::from($data['sex']));
        }

        if (!empty($data['dateOfBirth'])) {
            $user->setDateOfBirth(new DateTime($data['dateOfBirth']));
        }

        if (!empty($data['avatarUrl'])) {
            $user->setAvatarUrl($data['avatarUrl']);
        }

        return $user;
    }

    public function findByEmail(string $email): ?Users
    {
        $sql = "select * from users where email = ?";

        $statement = $this->db->prepare($sql);
        $statement->execute([$email]);
        
        $data = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $user = new Users();
        $user->setId((int)$data['id']);
        $user->setUsername($data['username']);
        $user->setPassword($data['password']);
        $user->setEmail($data['email']);
        $user->setRole(Role::from($data['role'] ?? 'BUYER'));
        $user->setAccountStatus(AccountStatus::from($data['accountStatus'] ?? 'ACTIVE'));

        if (!empty($data['fullName'])) {
            $user->setFullName($data['fullName']);
        }

        if (!empty($data['sex'])) {
            $user->setSex(Sex::from($data['sex']));
        }

        if (!empty($data['dateOfBirth'])) {
            $user->setDateOfBirth(new DateTime($data['dateOfBirth']));
        }

        if (!empty($data['avatarUrl'])) {
            $user->setAvatarUrl($data['avatarUrl']);
        }

        return $user;
    }

    public function updatePasswordByEmail(string $email, string $hashedPassword): bool
    {
        $sql = "update users set password = ? where email = ?";

        $statement = $this->db->prepare($sql);
        return $statement->execute([$hashedPassword, $email]);
    }

    public function getAllUserByPage(
        int $page,
        int $limit
    ): array {
        $offset = ($page - 1) * $limit;

        $limit = (int)$limit;
        $offset = (int)$offset;

        $sql = "select * from users order by id limit $limit offset $offset";

        $statement = $this->db->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    } 

    public function getUserByUsername(string $username): mixed
    {
        $sql = "select * from users where username = ?";

        $statement = $this->db->prepare($sql);
        $statement->execute([$username]);

        return $statement->fetch();
    }

    public function deleteUserByUsername(string $username): bool {
        $sql = "delete from users where username = ?";
        $statement = $this->db->prepare($sql);
        return $statement->execute([$username]);
    }

    public function updateUser(Users $user): bool  {
        $sql = "update users set email = ?, fullName = ?, sex = ?, dateOfBirth = ?, avatarUrl = ? where username = ?";
        $statement = $this->db->prepare($sql);
        
        $sexValue = $user->getSex() !== null ? $user->getSex()->value : null;
        $dobValue = $user->getDateOfBirth() !== null ? $user->getDateOfBirth()->format('Y-m-d') : null;
        
        return $statement->execute([
            $user->getEmail(),
            $user->getFullName(),
            $sexValue,
            $dobValue,
            $user->getAvatarUrl(),
            $user->getUsername()
        ]);
    }

    public function updateAccountStatus(string $username, string $status): bool {
        $sql = "update users set accountStatus = ? where username = ?";
        $statement = $this->db->prepare($sql);
        return $statement->execute([$status, $username]);
    }

    public function updateRole(string $username, string $role): bool {
        $sql = "update users set role = ? where username = ?";
        $statement = $this->db->prepare($sql);
        return $statement->execute([$role, $username]);
    }
}