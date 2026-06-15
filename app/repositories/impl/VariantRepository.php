<?php

class VariantRepository implements IVariantRepository
{
    public function __construct(
        private PDO $db
    ) {}

    public function findAllByProductId(int $productId): array
    {
        $sql = 'SELECT * FROM productVariants WHERE productId = ?';
        $stmt = $this->db->prepare($sql);
        
        $stmt->execute([$productId]);
        $productVariant = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $productVariant ?: null;
    }

    public function findById(int $id): ?ProductVariants
    {
        $sql = 'SELECT * FROM productVariants WHERE id = ?';
        $stmt = $this->db->prepare($sql);

        $stmt->execute([$id]);

        $productVariant = $stmt->fetch(PDO::FETCH_ASSOC);
        return $productVariant ?: null;
        }

    public function create(ProductVariants $variant): bool
    {
        $sql = 'INSERT INTO productVariants (productId, stock, options, price) VALUES (?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $variant->getProductId(),
            $variant->getStock(),
            $variant->getOptions(),
            $variant->getPrice(),
        ]);
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
