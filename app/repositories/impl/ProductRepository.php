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
        $stmt = $this->db->prepare("SELECT * FROM product WHERE shopId = ?");
        $stmt->execute([$shopId]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as &$product) {
            $stmtVar = $this->db->prepare("SELECT * FROM productVariants WHERE productId = ?");
            $stmtVar->execute([$product['id']]);
            $product['variants'] = $stmtVar->fetchAll(PDO::FETCH_ASSOC);
        }

        return $products;
    }

    public function create(Product $product): int
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

        $success = $stmt->execute([
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

        if ($success) {
            return (int)$this->db->lastInsertId();
        }
        return 0;
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
        try {
            $this->db->beginTransaction();

            
            $stmt = $this->db->prepare("SELECT id FROM productVariants WHERE productId = ?");
            $stmt->execute([$id]);
            $variantIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($variantIds)) {
                $inQuery = implode(',', array_fill(0, count($variantIds), '?'));

                
                $stmt = $this->db->prepare("SELECT id FROM reviews WHERE productVariantId IN ($inQuery)");
                $stmt->execute($variantIds);
                $reviewIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

                if (!empty($reviewIds)) {
                    $reviewInQuery = implode(',', array_fill(0, count($reviewIds), '?'));
                    $stmt = $this->db->prepare("DELETE FROM reviewImages WHERE reviewId IN ($reviewInQuery)");
                    $stmt->execute($reviewIds);
                }

                
                $stmt = $this->db->prepare("DELETE FROM reviews WHERE productVariantId IN ($inQuery)");
                $stmt->execute($variantIds);

                
                $stmt = $this->db->prepare("DELETE FROM productImages WHERE productVariantId IN ($inQuery)");
                $stmt->execute($variantIds);

                
                $stmt = $this->db->prepare("DELETE FROM cartItem WHERE productVariantId IN ($inQuery)");
                $stmt->execute($variantIds);

                
                $stmt = $this->db->prepare("DELETE FROM orderItem WHERE productVariantId IN ($inQuery)");
                $stmt->execute($variantIds);

                
                $stmt = $this->db->prepare("DELETE FROM productVariants WHERE productId = ?");
                $stmt->execute([$id]);
            }

            
            $stmt = $this->db->prepare("DELETE FROM product WHERE id = ?");
            $success = $stmt->execute([$id]);

            $this->db->commit();
            return $success;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }
}