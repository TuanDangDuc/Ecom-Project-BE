<?php

class ReviewRepository implements IReviewRepository
{
    public function __construct(
        private PDO $db
    ){}

    public function createReview(array $reviewData): int
    {
        $sql = "INSERT INTO reviews (orderItemId, productVariantId, userId, rating, comment, shopReply)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $reviewData['orderItemId'],
            $reviewData['productVariantId'],
            $reviewData['userId'],
            $reviewData['rating'],
            $reviewData['comment'] ?? null,
            $reviewData['shopReply'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function findReviewById(int $reviewId): ?array
    {
        $sql = "SELECT * FROM reviews WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$reviewId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    public function findReviewByOrderItemId(int $orderItemId): ?array
    {
        $sql = "SELECT * FROM reviews WHERE orderItemId = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderItemId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    public function findProductReviews(int $productId): array
    {
        $sql = "SELECT 
                    r.id AS id,
                    r.orderItemId,
                    r.productVariantId,
                    r.userId,
                    r.rating,
                    r.comment,
                    r.shopReply,
                    r.createAt,
                    u.fullName AS userFullName,
                    u.username AS userUsername
                FROM reviews r
                JOIN users u ON r.userId = u.id
                JOIN productVariants pv ON r.productVariantId = pv.id
                WHERE pv.productId = ?
                ORDER BY r.createAt DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addReviewImage(int $reviewId, string $url, int $imageOrder): bool
    {
        $sql = "INSERT INTO reviewImages (reviewId, url, imageOrder) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$reviewId, $url, $imageOrder]);
    }

    public function deleteReviewImage(int $reviewImageId): bool
    {
        $sql = "DELETE FROM reviewImages WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$reviewImageId]);
    }

    public function findReviewImageById(int $reviewImageId): ?array
    {
        $sql = "SELECT * FROM reviewImages WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$reviewImageId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    public function getReviewImages(int $reviewId): array
    {
        $sql = "SELECT id, url, imageOrder FROM reviewImages WHERE reviewId = ? ORDER BY imageOrder ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$reviewId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderItemDetails(int $orderItemId): ?array
    {
        // Join with orders to get userId and check status
        $sql = "SELECT oi.id, oi.orderId, oi.orderStatus, oi.productVariantId, o.userId, pv.productId
                FROM orderItem oi
                JOIN orders o ON oi.orderId = o.id
                JOIN productVariants pv ON oi.productVariantId = pv.id
                WHERE oi.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderItemId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    public function updateProductRatingAverage(int $productId): bool
    {
        // Calculate average
        $sql = "SELECT AVG(r.rating) AS avgRating 
                FROM reviews r
                JOIN productVariants pv ON r.productVariantId = pv.id
                WHERE pv.productId = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $avg = $res && $res['avgRating'] !== null ? (float)$res['avgRating'] : 0.00;

        // Update product
        $updateSql = "UPDATE product SET ratingAverage = ? WHERE id = ?";
        $updateStmt = $this->db->prepare($updateSql);
        return $updateStmt->execute([$avg, $productId]);
    }
}
