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
        
        $data = $statement->fetch();

        if (!$data) {
            return null;
        }

        return new Users($data['username'], $data['password'], Role::from($data['role'] ?? 'USER'));
    }

    public function findByEmail(string $email): ?Users
    {
        $sql = "select * from users where email = ?";

        $statement = $this->db->prepare($sql);
        $statement->execute([$email]);
        
        $data = $statement->fetch();

        if (!$data) {
            return null;
        }

        return new Users($data['username'], $data['password'], Role::from($data['role'] ?? 'USER'));
    }

    public function updatePasswordByEmail(string $email, string $hashedPassword): bool
    {
        $sql = "update users set password = ? where email = ?";

        $statement = $this->db->prepare($sql);
        return $statement->execute([$hashedPassword, $email]);
    }


}