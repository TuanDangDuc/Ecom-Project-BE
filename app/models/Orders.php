<?php
class Orders
{
    private int $id;
    private String $orderCode;
    private String $recipientName;
    private String $recipientPhone;
    private String $note;
    private float $subtotal;
    private float $shippingFee;
    private float $totalAmount;
    private DataTime $createdAt;
    private DataTime $updatedAt;

    private int $userId;
    private int $shippingAddressId;
}