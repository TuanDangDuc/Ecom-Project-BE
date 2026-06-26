<?php

class ProductRepository implements IProductRepository
{
    public function __construct(
        private PDO $db
    ) {}

    public function findAll(array $filters = []): array
    {
        $sql = "SELECT * FROM product";
        $conditions = [];
        $params = [];

        if (isset($filters['search']) && trim($filters['search']) !== '') {
            $conditions[] = "name LIKE ?";
            $params[] = '%' . trim($filters['search']) . '%';
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sort = $filters['sort'] ?? null;
        switch ($sort) {
            case 'price_asc':
                $sql .= " ORDER BY basePrice ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY basePrice DESC";
                break;
            case 'rating_asc':
                $sql .= " ORDER BY ratingAverage ASC";
                break;
            case 'rating_desc':
                $sql .= " ORDER BY ratingAverage DESC";
                break;
            default:
                $sql .= " ORDER BY id DESC";
                break;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

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

    public function showProductByCategory(int $categoryId): array
    {
        $stmt = $this->db->prepare( "SELECT * From product Where categoryId = ?");
        
        $stmt->execute([$categoryId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    }

    public function showShopProduct(int $shopId): array
    {
        $stmt = $this->db->prepare( "SELECT * From product Where shopId = ?");
        
        $stmt->execute([$shopId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
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