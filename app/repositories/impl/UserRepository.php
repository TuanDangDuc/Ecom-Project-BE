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
}