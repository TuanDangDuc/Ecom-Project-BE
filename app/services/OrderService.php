<?php

class OrderService
{
    private IOrderRepository $orderRepository;
    private ICartRepository $cartRepository;

    public function __construct(IOrderRepository $orderRepository, ICartRepository $cartRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->cartRepository = $cartRepository;
    }

    public function checkout(int $userId, array $checkoutData): array
    {
        $recipientName = trim($checkoutData['recipientName'] ?? '');
        $recipientPhone = trim($checkoutData['recipientPhone'] ?? '');
        $note = trim($checkoutData['note'] ?? '');
        $shippingAddressId = isset($checkoutData['shippingAddressId']) ? (int)$checkoutData['shippingAddressId'] : null;
        $cartItemIds = $checkoutData['cartItemIds'] ?? [];

        if (empty($recipientName) || empty($recipientPhone)) {
            return ['success' => false, 'message' => 'Recipient name and phone are required.'];
        }

        $cart = $this->cartRepository->findCartByUserId($userId);
        if (!$cart) {
            return ['success' => false, 'message' => 'Cart not found.'];
        }

        $cartItems = $this->cartRepository->getCartItems((int)$cart['id']);
        if (empty($cartItems)) {
            return ['success' => false, 'message' => 'Cart is empty.'];
        }

        $checkoutItems = [];
        if (!empty($cartItemIds)) {
            $ids = array_map('intval', $cartItemIds);
            foreach ($cartItems as $item) {
                if (in_array((int)$item['id'], $ids)) {
                    $checkoutItems[] = $item;
                }
            }
            if (empty($checkoutItems)) {
                return ['success' => false, 'message' => 'No matching items in cart for checkout.'];
            }
        } else {
            $checkoutItems = $cartItems;
        }

        try {
            $this->orderRepository->beginTransaction();

            $subtotal = 0.00;
            foreach ($checkoutItems as $item) {
                $stock = $this->orderRepository->getVariantStock((int)$item['productVariantId']);
                if ($stock < (int)$item['quantity']) {
                    throw new Exception("Product variant {$item['productName']} is out of stock (Available: $stock, Requested: {$item['quantity']}).");
                }
                $subtotal += (float)$item['currentPrice'] * (int)$item['quantity'];
            }

            $shippingFee = $subtotal > 500000 ? 0.00 : 30000.00;
            $totalAmount = $subtotal + $shippingFee;

            $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));

            $orderId = $this->orderRepository->createOrder([
                'orderCode' => $orderCode,
                'userId' => $userId,
                'recipientName' => $recipientName,
                'recipientPhone' => $recipientPhone,
                'note' => $note,
                'subtotal' => $subtotal,
                'shippingFee' => $shippingFee,
                'totalAmount' => $totalAmount,
                'shippingAddressId' => $shippingAddressId
            ]);

            foreach ($checkoutItems as $item) {
                $currentStock = $this->orderRepository->getVariantStock((int)$item['productVariantId']);
                $this->orderRepository->updateVariantStock((int)$item['productVariantId'], $currentStock - (int)$item['quantity']);

                $this->orderRepository->createOrderItem([
                    'orderId' => $orderId,
                    'quantity' => (int)$item['quantity'],
                    'priceAtPurchase' => (float)$item['currentPrice'],
                    'orderStatus' => 'PENDING',
                    'productVariantId' => (int)$item['productVariantId']
                ]);

                $this->cartRepository->deleteCartItem((int)$item['id']);
            }

            $remainingItems = $this->cartRepository->getCartItems((int)$cart['id']);
            $remainingTotal = 0.00;
            foreach ($remainingItems as $remItem) {
                $remainingTotal += (float)$remItem['currentPrice'] * (int)$remItem['quantity'];
            }
            $this->cartRepository->updateCartTotalCost((int)$cart['id'], $remainingTotal);

            $this->orderRepository->commitTransaction();

            return [
                'success' => true,
                'message' => 'Order placed successfully.',
                'orderId' => $orderId,
                'orderCode' => $orderCode
            ];

        } catch (Exception $e) {
            $this->orderRepository->rollbackTransaction();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getUserOrders(int $userId): array
    {
        $orders = $this->orderRepository->findUserOrders($userId);
        foreach ($orders as &$order) {
            $order['items'] = $this->orderRepository->getOrderItems((int)$order['id']);
        }
        return ['success' => true, 'orders' => $orders];
    }

    public function getOrderDetail(int $userId, int $orderId): array
    {
        $order = $this->orderRepository->findOrderById($orderId);
        if (!$order) {
            return ['success' => false, 'message' => 'Order not found.'];
        }

        if ((int)$order['userId'] !== $userId) {
            return ['success' => false, 'message' => 'Unauthorized view.'];
        }

        $order['items'] = $this->orderRepository->getOrderItems($orderId);
        return ['success' => true, 'order' => $order];
    }

    public function updateOrderStatus(int $userId, string $role, int $orderId, string $status): array
    {
        $allowedStatuses = ['PENDING', 'CONFIRMED', 'SHIPPING', 'COMPLETED', 'CANCELED'];
        $status = strtoupper($status);
        if (!in_array($status, $allowedStatuses)) {
            return ['success' => false, 'message' => 'Invalid status value.'];
        }

        $order = $this->orderRepository->findOrderById($orderId);
        if (!$order) {
            return ['success' => false, 'message' => 'Order not found.'];
        }

        $isOwner = ((int)$order['userId'] === $userId);
        $isAdminOrSeller = ($role === 'ADMIN' || $role === 'SELLER');

        if (!$isOwner && !$isAdminOrSeller) {
            return ['success' => false, 'message' => 'Unauthorized action.'];
        }

        $items = $this->orderRepository->getOrderItems($orderId);
        if (empty($items)) {
            return ['success' => false, 'message' => 'No items in this order.'];
        }

        $currentStatus = $items[0]['orderStatus'];

        if ($isOwner && !$isAdminOrSeller) {
            if ($status !== 'CANCELED') {
                return ['success' => false, 'message' => 'Buyers can only cancel their orders.'];
            }
            if ($currentStatus !== 'PENDING' && $currentStatus !== 'CANCELED') {
                return ['success' => false, 'message' => 'Orders can only be canceled if they are still PENDING.'];
            }
        }

        try {
            $this->orderRepository->beginTransaction();

            $this->orderRepository->updateOrderStatusByOrderId($orderId, $status);

            if ($status === 'CANCELED' && $currentStatus !== 'CANCELED') {
                $this->orderRepository->setOrderCanceledTime($orderId);
                foreach ($items as $item) {
                    $currentStock = $this->orderRepository->getVariantStock((int)$item['productVariantId']);
                    $this->orderRepository->updateVariantStock((int)$item['productVariantId'], $currentStock + (int)$item['quantity']);
                }
            }

            $this->orderRepository->commitTransaction();
            return ['success' => true, 'message' => "Order status updated to $status successfully."];

        } catch (Exception $e) {
            $this->orderRepository->rollbackTransaction();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
