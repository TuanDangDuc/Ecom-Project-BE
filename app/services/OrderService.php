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

    public function checkout(int $userId, CheckoutDtoRequest $request): array
    {
        $err = $request->validate();
        if ($err) {
            return ['success' => false, 'message' => $err];
        }

        $cart = $this->cartRepository->findCartByUserId($userId);
        if (!$cart) {
            return ['success' => false, 'message' => 'Cart not found.'];
        }

        $cartItems = $this->cartRepository->getCartItems($cart->getId());
        if (empty($cartItems)) {
            return ['success' => false, 'message' => 'Cart is empty.'];
        }

        $checkoutItems = [];
        if (!empty($request->cartItemIds)) {
            $ids = array_map('intval', $request->cartItemIds);
            foreach ($cartItems as $item) {
                if (in_array($item->getId(), $ids)) {
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
                $stock = $this->orderRepository->getVariantStock($item->getProductVariantId());
                if ($stock < $item->getQuantity()) {
                    throw new Exception("Product variant {$item->getProductName()} is out of stock (Available: $stock, Requested: {$item->getQuantity()}).");
                }
                $subtotal += (float)$item->getCurrentPrice() * $item->getQuantity();
            }

            $shippingFee = $subtotal > 500000 ? 0.00 : 30000.00;
            $totalAmount = $subtotal + $shippingFee;

            $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));

            $order = new Orders([
                'orderCode' => $orderCode,
                'userId' => $userId,
                'recipientName' => $request->recipientName,
                'recipientPhone' => $request->recipientPhone,
                'note' => $request->note,
                'subtotal' => $subtotal,
                'shippingFee' => $shippingFee,
                'totalAmount' => $totalAmount,
                'shippingAddressId' => $request->shippingAddressId
            ]);

            $orderId = $this->orderRepository->createOrder($order);

            foreach ($checkoutItems as $item) {
                $currentStock = $this->orderRepository->getVariantStock($item->getProductVariantId());
                $this->orderRepository->updateVariantStock($item->getProductVariantId(), $currentStock - $item->getQuantity());

                $orderItem = new OrderItem([
                    'orderId' => $orderId,
                    'quantity' => $item->getQuantity(),
                    'priceAtPurchase' => (float)$item->getCurrentPrice(),
                    'orderStatus' => 'PENDING',
                    'productVariantId' => $item->getProductVariantId()
                ]);

                $this->orderRepository->createOrderItem($orderItem);
                $this->cartRepository->deleteCartItem($item->getId());
            }

            $remainingItems = $this->cartRepository->getCartItems($cart->getId());
            $remainingTotal = 0.00;
            foreach ($remainingItems as $remItem) {
                $remainingTotal += (float)$remItem->getCurrentPrice() * $remItem->getQuantity();
            }
            $this->cartRepository->updateCartTotalCost($cart->getId(), $remainingTotal);

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
        
        $ordersArray = [];
        foreach ($orders as $order) {
            $orderArr = $order->toArray();
            
            $items = $this->orderRepository->getOrderItems($order->getId());
            $itemsArray = [];
            foreach ($items as $item) {
                $itemsArray[] = $item->toArray();
            }
            
            $orderArr['items'] = $itemsArray;
            $ordersArray[] = $orderArr;
        }

        return ['success' => true, 'orders' => $ordersArray];
    }

    public function getOrderDetail(int $userId, int $orderId): array
    {
        $order = $this->orderRepository->findOrderById($orderId);
        if (!$order) {
            return ['success' => false, 'message' => 'Order not found.'];
        }

        if ($order->getUserId() !== $userId) {
            return ['success' => false, 'message' => 'Unauthorized view.'];
        }

        $orderArr = $order->toArray();
        $items = $this->orderRepository->getOrderItems($orderId);
        $itemsArray = [];
        foreach ($items as $item) {
            $itemsArray[] = $item->toArray();
        }
        $orderArr['items'] = $itemsArray;

        return ['success' => true, 'order' => $orderArr];
    }

    public function updateOrderStatus(int $userId, string $role, int $orderId, UpdateOrderStatusDtoRequest $request): array
    {
        $err = $request->validate();
        if ($err) {
            return ['success' => false, 'message' => $err];
        }
        
        $status = strtoupper($request->status);

        $order = $this->orderRepository->findOrderById($orderId);
        if (!$order) {
            return ['success' => false, 'message' => 'Order not found.'];
        }

        $isOwner = ($order->getUserId() === $userId);
        $isAdminOrSeller = ($role === 'ADMIN' || $role === 'SELLER');

        if (!$isOwner && !$isAdminOrSeller) {
            return ['success' => false, 'message' => 'Unauthorized action.'];
        }

        $items = $this->orderRepository->getOrderItems($orderId);
        if (empty($items)) {
            return ['success' => false, 'message' => 'No items in this order.'];
        }

        $currentStatus = $items[0]->getOrderStatus();

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
                    $currentStock = $this->orderRepository->getVariantStock($item->getProductVariantId());
                    $this->orderRepository->updateVariantStock($item->getProductVariantId(), $currentStock + $item->getQuantity());
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
