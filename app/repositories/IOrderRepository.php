<?php

interface IOrderRepository
{
    function beginTransaction(): void;
    function commitTransaction(): void;
    function rollbackTransaction(): void;
    function createOrder(Orders $order): int;
    function createOrderItem(OrderItem $item): bool;
    function findOrderById(int $orderId): ?Orders;
    function findUserOrders(int $userId): array;
    function getOrderItems(int $orderId): array;
    function updateOrderItemStatus(int $orderItemId, string $status): bool;
    function updateOrderStatusByOrderId(int $orderId, string $status): bool;
    function getOrderItemById(int $orderItemId): ?OrderItem;
    function getVariantStock(int $productVariantId): int;
    function updateVariantStock(int $productVariantId, int $newStock): bool;
    function setOrderCanceledTime(int $orderId): bool;
}
