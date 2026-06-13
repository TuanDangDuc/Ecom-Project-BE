<?php
class ProductTypesRepository implements IProductTypesRepository
{
    public function __construct(
        private PDO $db
    ){}

    public function getAll(): array
    {
        $sql = "select * from productType";

        $statement = $this->db->prepare($sql);
        $statement->execute();
        
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkExistsByName(string $name): bool
    {
        $sql = "select * from productType where name = ?";

        $statement = $this->db->prepare($sql);
        $statement->execute([$name]);
        
        return $statement->fetch() !== false;
    }

    public function create(ProductType $productType): bool
    {
        $sql = "insert into productType (name, description) values (?, ?)";

        $statement = $this->db->prepare($sql);

        return $statement->execute([$productType->getName(), $productType->getDescription()]);
    }
}