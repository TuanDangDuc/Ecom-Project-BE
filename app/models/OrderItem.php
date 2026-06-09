<?php
class OrderItem {
    private int $id;
    private int $quantity;
    private float $priceAtPurchase;
    private OrderStatus $orderStatus;
    private int $trackingNumber;
    private String $shippingProvider;

    private int $orderId;
    private int $productVariantId;
}