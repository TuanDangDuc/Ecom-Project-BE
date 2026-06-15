<?php

class ProductRepository implements IProductRepository
{
    public function __construct(
        private PDO $db
    ) {}

    public function findAll(): array
    {
        $sql = "SELECT * FROM product";
        $stmt = $this->db->prepare($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM product WHERE id = ?"
        );

        $stmt->execute([$id]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        return $product ?: null;
    }

    public function create(Product $product): bool
    {
        $sql = "
        INSERT INTO product
        (
            name,
            typeId,
            thumbnailUrl,
            imagesUrl,
            basePrice,
            categoryId,
            ratingAverage,
            shopId,
            description
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $product->getName(),
            $product->getProductTypeId(),
            $product->getThumbnailUrl(),
            $product->getImageUrl(),
            $product->getBasePrice(),
            $product->getCategoryId(),
            $product->getRatingAverage(),
            $product->getShopId(),
            $product->getDescription()
        ]);

    }

    public function update(int $id, Product $product): bool
    {
        $sql = "
        UPDATE product
        SET
            name = ?,
            typeId = ?,
            thumbnailUrl = ?,
            imagesUrl = ?,
            basePrice = ?,
            categoryId = ?,
            ratingAverage = ?,
            shopId = ?,
            description = ?
        WHERE id = ?
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $product->getName(),
            $product->getProductTypeId(),
            $product->getThumbnailUrl(),
            $product->getImageUrl(),
            $product->getBasePrice(),
            $product->getCategoryId(),
            $product->getRatingAverage(),
            $product->getShopId(),
            $product->getDescription(),
            $id
        ]);

    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM product WHERE id = ?"
        );

        return $stmt->execute([$id]);
    }
}