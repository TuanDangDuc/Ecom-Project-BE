<?php

interface IOrderRepository
{
    public function beginTransaction(): void;
    public function commitTransaction(): void;
    public function rollbackTransaction(): void;
    public function createOrder(array $orderData): int;
    public function createOrderItem(array $itemData): bool;
    public function findOrderById(int $orderId): ?array;
    public function findUserOrders(int $userId): array;
    public function getOrderItems(int $orderId): array;
    public function updateOrderItemStatus(int $orderItemId, string $status): bool;
    public function updateOrderStatusByOrderId(int $orderId, string $status): bool;
    public function getOrderItemById(int $orderItemId): ?array;
    public function getVariantStock(int $productVariantId): int;
    public function updateVariantStock(int $productVariantId, int $newStock): bool;
    public function setOrderCanceledTime(int $orderId): bool;
}
