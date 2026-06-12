<?php

class CartService
{
    private ICartRepository $cartRepository;

    public function __construct(ICartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function getCart(int $userId): array
    {
        $cart = $this->cartRepository->findCartByUserId($userId);
        if (!$cart) {
            $cartId = $this->cartRepository->createCart($userId);
            $cart = [
                'id' => $cartId,
                'userId' => $userId,
                'totalCost' => 0.00
            ];
        }

        $items = $this->cartRepository->getCartItems((int)$cart['id']);

        $totalCost = 0.00;
        foreach ($items as $item) {
            $totalCost += (float)$item['currentPrice'] * (int)$item['quantity'];
        }

        if (abs((float)$cart['totalCost'] - $totalCost) > 0.001) {
            $this->cartRepository->updateCartTotalCost((int)$cart['id'], $totalCost);
            $cart['totalCost'] = $totalCost;
        }

        return [
            'success' => true,
            'cart' => [
                'id' => (int)$cart['id'],
                'userId' => (int)$cart['userId'],
                'totalCost' => (float)$cart['totalCost'],
                'items' => $items
            ]
        ];
    }

    public function addToCart(int $userId, int $productVariantId, int $quantity): array
    {
        if ($quantity <= 0) {
            return ['success' => false, 'message' => 'Quantity must be greater than zero.'];
        }

        $variant = $this->cartRepository->getVariantStockAndPrice($productVariantId);
        if (!$variant) {
            return ['success' => false, 'message' => 'Product variant not found.'];
        }

        $cart = $this->cartRepository->findCartByUserId($userId);
        if (!$cart) {
            $cartId = $this->cartRepository->createCart($userId);
            $cart = ['id' => $cartId];
        }

        $cartId = (int)$cart['id'];

        $existingItem = $this->cartRepository->findCartItem($cartId, $productVariantId);
        $newQuantity = $quantity;
        if ($existingItem) {
            $newQuantity += (int)$existingItem['quantity'];
        }

        if ($newQuantity > (int)$variant['stock']) {
            return [
                'success' => false,
                'message' => "Cannot add item. Requested quantity ($newQuantity) exceeds available stock ({$variant['stock']})."
            ];
        }

        if ($existingItem) {
            $success = $this->cartRepository->updateCartItemQuantity((int)$existingItem['id'], $newQuantity);
        } else {
            $success = $this->cartRepository->addCartItem($cartId, $productVariantId, $quantity, (float)$variant['price']);
        }

        if ($success) {
            $this->recalculateCartTotal($cartId);
            return ['success' => true, 'message' => 'Item added to cart successfully.'];
        }

        return ['success' => false, 'message' => 'Failed to add item to cart.'];
    }

    public function updateCartItem(int $userId, int $cartItemId, int $quantity): array
    {
        if ($quantity <= 0) {
            return ['success' => false, 'message' => 'Quantity must be greater than zero.'];
        }

        $cartItem = $this->cartRepository->findCartItemById($cartItemId);
        if (!$cartItem) {
            return ['success' => false, 'message' => 'Cart item not found.'];
        }

        $cart = $this->cartRepository->findCartByUserId($userId);
        if (!$cart || (int)$cartItem['cartId'] !== (int)$cart['id']) {
            return ['success' => false, 'message' => 'Unauthorized or cart mismatch.'];
        }

        $variant = $this->cartRepository->getVariantStockAndPrice((int)$cartItem['productVariantId']);
        if (!$variant) {
            return ['success' => false, 'message' => 'Product variant not found.'];
        }

        if ($quantity > (int)$variant['stock']) {
            return [
                'success' => false,
                'message' => "Cannot update quantity. Requested quantity ($quantity) exceeds available stock ({$variant['stock']})."
            ];
        }

        $success = $this->cartRepository->updateCartItemQuantity($cartItemId, $quantity);
        if ($success) {
            $this->recalculateCartTotal((int)$cart['id']);
            return ['success' => true, 'message' => 'Cart updated successfully.'];
        }

        return ['success' => false, 'message' => 'Failed to update cart.'];
    }

    public function removeFromCart(int $userId, int $cartItemId): array
    {
        $cartItem = $this->cartRepository->findCartItemById($cartItemId);
        if (!$cartItem) {
            return ['success' => false, 'message' => 'Cart item not found.'];
        }

        $cart = $this->cartRepository->findCartByUserId($userId);
        if (!$cart || (int)$cartItem['cartId'] !== (int)$cart['id']) {
            return ['success' => false, 'message' => 'Unauthorized or cart mismatch.'];
        }

        $success = $this->cartRepository->deleteCartItem($cartItemId);
        if ($success) {
            $this->recalculateCartTotal((int)$cart['id']);
            return ['success' => true, 'message' => 'Item removed from cart.'];
        }

        return ['success' => false, 'message' => 'Failed to remove item from cart.'];
    }

    private function recalculateCartTotal(int $cartId): void
    {
        $items = $this->cartRepository->getCartItems($cartId);
        $totalCost = 0.00;
        foreach ($items as $item) {
            $totalCost += (float)$item['currentPrice'] * (int)$item['quantity'];
        }
        $this->cartRepository->updateCartTotalCost($cartId, $totalCost);
    }
}
