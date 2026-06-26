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

    public function checkExistsById(int $id): bool
    {
        $sql = "select * from category where id = ?";

        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        
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

    public function update(int $id, string $name): bool
    {
        $sql = "UPDATE category SET name = ? WHERE id = ?";
        $statement = $this->db->prepare($sql);
        return $statement->execute([$name, $id]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM category WHERE id = ?";
        $statement = $this->db->prepare($sql);
        return $statement->execute([$id]);
    }
}