<?php

class ProductImageRepository implements IProductImageRepository
{
    public function __construct(
        private PDO $db
    ) {}


    public function create(ProductImages $image): bool
    {
        $sql = 'INSERT INTO productImages (url, imageOrder, productVariantId) VALUES (?, ?, ?)';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $image->getUrl(),
            $image->getImageOrder(),
            $image->getProductVariantId(),
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM productImages WHERE id = ?';
        $stmt = $this->db->prepare($sql);

        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }
}
