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

    public function createOrder(Orders $order): int
    {
        $sql = "INSERT INTO orders (orderCode, userId, recipientName, recipientPhone, note, subtotal, shippingFee, totalAmount, shippingAddressId)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $order->getOrderCode(),
            $order->getUserId(),
            $order->getRecipientName(),
            $order->getRecipientPhone(),
            $order->getNote(),
            $order->getSubtotal(),
            $order->getShippingFee(),
            $order->getTotalAmount(),
            $order->getShippingAddressId()
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function createOrderItem(OrderItem $item): bool
    {
        $sql = "INSERT INTO orderItem (orderId, quantity, priceAtPurchase, orderStatus, trackingNumber, shippingProvider, productVariantId)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $item->getOrderId(),
            $item->getQuantity(),
            $item->getPriceAtPurchase(),
            $item->getOrderStatus(),
            $item->getTrackingNumber(),
            $item->getShippingProvider(),
            $item->getProductVariantId()
        ]);
    }

    public function findOrderById(int $orderId): ?Orders
    {
        $sql = "SELECT * FROM orders WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? new Orders($result) : null;
    }

    public function findUserOrders(int $userId): array
    {
        $sql = "SELECT * FROM orders WHERE userId = ? ORDER BY createdAt DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $orders = [];
        foreach ($results as $row) {
            $orders[] = new Orders($row);
        }
        return $orders;
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

        $orderItemObjects = [];
        foreach ($items as $item) {
            if (isset($item['variantOptions'])) {
                $item['variantOptions'] = json_decode($item['variantOptions'], true);
            }
            $orderItemObjects[] = new OrderItem($item);
        }
        return $orderItemObjects;
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

    public function getOrderItemById(int $orderItemId): ?OrderItem
    {
        $sql = "SELECT * FROM orderItem WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderItemId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? new OrderItem($result) : null;
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
