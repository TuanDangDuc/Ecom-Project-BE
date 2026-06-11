<?php
class CategoriesRepository implements ICategoriesRepository
{
    public function __construct(
        private PDO $db
    ){}

    public function getAll(): array
    {
        $sql = "select * from category";

        $statement = $this->db->prepare($sql);
        $statement->execute();
        
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkExistsByName(string $name): bool
    {
        $sql = "select * from category where name = ?";

        $statement = $this->db->prepare($sql);
        $statement->execute([$name]);
        
        return $statement->fetch() !== false;
    }

    public function create(string $name): bool
    {
        $sql = "insert into category (name) values (?)";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            $name
        ]);

    }
}