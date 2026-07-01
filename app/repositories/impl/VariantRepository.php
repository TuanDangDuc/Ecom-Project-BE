<?php

class VariantRepository implements IVariantRepository
{
    public function __construct(
        private PDO $db
    ) {}

    public function findAllByProductId(int $productId): array
    {
        $sql = '
            SELECT pv.*, 
                (SELECT pi.url FROM productImages pi WHERE pi.productVariantId = pv.id ORDER BY pi.imageOrder ASC LIMIT 1) as imageUrl 
            FROM productVariants pv 
            WHERE pv.productId = ?
        ';
        $stmt = $this->db->prepare($sql);
        
        $stmt->execute([$productId]);
        $productVariant = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $productVariant ?: [];
    }

    public function findById(int $id): ?ProductVariants
    {
        $sql = 'SELECT * FROM productVariants WHERE id = ?';
        $stmt = $this->db->prepare($sql);

        $stmt->execute([$id]);

        $productVariant = $stmt->fetch(PDO::FETCH_ASSOC);
        return $productVariant ?ProductVariants::fromArray($productVariant) : null;
        }

    public function create(ProductVariants $variant): int
    {
        $sql = 'INSERT INTO productVariants (productId, stock, options, price) VALUES (?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);

        $success = $stmt->execute([
            $variant->getProductId(),
            $variant->getStock(),
            $variant->getOptions(),
            $variant->getPrice(),
        ]);

        if ($success) {
            return (int)$this->db->lastInsertId();
        }
        return 0;
    }

    public function update(int $id, ProductVariants $variant): bool
    {
        $sql = 'UPDATE productVariants SET productId = ?, stock = ?, options = ?, price = ? WHERE id = ?';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $variant->getProductId(),
            $variant->getStock(),
            $variant->getOptions(),
            $variant->getPrice(),
            $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM productVariants WHERE id = ?');

        return $stmt->execute([$id]);
    }
}
