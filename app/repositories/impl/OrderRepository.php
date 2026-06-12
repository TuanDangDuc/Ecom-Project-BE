<?php

class OrderRepository implements IOrderRepository
{
    public function __construct(
        private PDO $db
    ){}

    public function beginTransaction(): void
    {
        $this->db->beginTransaction();
    }

    public function commitTransaction(): void
    {
        $this->db->commit();
    }

    public function rollbackTransaction(): void
    {
        $this->db->rollBack();
    }

    public function createOrder(array $orderData): int
    {
        $sql = "INSERT INTO orders (orderCode, userId, recipientName, recipientPhone, note, subtotal, shippingFee, totalAmount, shippingAddressId)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $orderData['orderCode'],
            $orderData['userId'],
            $orderData['recipientName'],
            $orderData['recipientPhone'],
            $orderData['note'],
            $orderData['subtotal'],
            $orderData['shippingFee'],
            $orderData['totalAmount'],
            $orderData['shippingAddressId']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function createOrderItem(array $itemData): bool
    {
        $sql = "INSERT INTO orderItem (orderId, quantity, priceAtPurchase, orderStatus, trackingNumber, shippingProvider, productVariantId)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $itemData['orderId'],
            $itemData['quantity'],
            $itemData['priceAtPurchase'],
            $itemData['orderStatus'],
            $itemData['trackingNumber'] ?? null,
            $itemData['shippingProvider'] ?? null,
            $itemData['productVariantId']
        ]);
    }

    public function findOrderById(int $orderId): ?array
    {
        $sql = "SELECT * FROM orders WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    public function findUserOrders(int $userId): array
    {
        $sql = "SELECT * FROM orders WHERE userId = ? ORDER BY createdAt DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderItems(int $orderId): array
    {
        $sql = "SELECT 
                    oi.id AS id,
                    oi.orderId,
                    oi.quantity,
                    oi.priceAtPurchase,
                    oi.orderStatus,
                    oi.trackingNumber,
                    oi.shippingProvider,
                    oi.productVariantId,
                    pv.options AS variantOptions,
                    p.id AS productId,
                    p.name AS productName,
                    p.thumbnailUrl AS productThumbnail
                FROM orderItem oi
                JOIN productVariants pv ON oi.productVariantId = pv.id
                JOIN product p ON pv.productId = p.id
                WHERE oi.orderId = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as &$item) {
            if (isset($item['variantOptions'])) {
                $item['variantOptions'] = json_decode($item['variantOptions'], true);
            }
        }
        return $items;
    }

    public function updateOrderItemStatus(int $orderItemId, string $status): bool
    {
        $sql = "UPDATE orderItem SET orderStatus = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $orderItemId]);
    }

    public function updateOrderStatusByOrderId(int $orderId, string $status): bool
    {
        $sql = "UPDATE orderItem SET orderStatus = ? WHERE orderId = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $orderId]);
    }

    public function getOrderItemById(int $orderItemId): ?array
    {
        $sql = "SELECT * FROM orderItem WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderItemId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    public function getVariantStock(int $productVariantId): int
    {
        $sql = "SELECT stock FROM productVariants WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productVariantId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['stock'] : 0;
    }

    public function updateVariantStock(int $productVariantId, int $newStock): bool
    {
        $sql = "UPDATE productVariants SET stock = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$newStock, $productVariantId]);
    }

    public function setOrderCanceledTime(int $orderId): bool
    {
        $sql = "UPDATE orders SET canceledAt = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$orderId]);
    }
}
