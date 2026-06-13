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
            $newCart = new Carts(['userId' => $userId, 'totalCost' => 0.00]);
            $cartId = $this->cartRepository->createCart($newCart);
            $cart = new Carts(['id' => $cartId, 'userId' => $userId, 'totalCost' => 0.00]);
        }

        $items = $this->cartRepository->getCartItems($cart->getId());

        $totalCost = 0.00;
        $itemsArray = [];
        foreach ($items as $item) {
            $totalCost += (float)$item->getCurrentPrice() * (int)$item->getQuantity();
            $itemsArray[] = $item->toArray();
        }

        if (abs((float)$cart->getTotalCost() - $totalCost) > 0.001) {
            $this->cartRepository->updateCartTotalCost($cart->getId(), $totalCost);
            $cart->setTotalCost($totalCost);
        }

        $cartArray = $cart->toArray();
        $cartArray['items'] = $itemsArray;

        return [
            'success' => true,
            'cart' => $cartArray
        ];
    }

    public function addToCart(int $userId, AddToCartDtoRequest $request): array
    {
        $err = $request->validate();
        if ($err) {
            return ['success' => false, 'message' => $err];
        }

        $variant = $this->cartRepository->getVariantStockAndPrice($request->productVariantId);
        if (!$variant) {
            return ['success' => false, 'message' => 'Product variant not found.'];
        }

        $cart = $this->cartRepository->findCartByUserId($userId);
        if (!$cart) {
            $newCart = new Carts(['userId' => $userId, 'totalCost' => 0.00]);
            $cartId = $this->cartRepository->createCart($newCart);
            $cart = new Carts(['id' => $cartId]);
        }

        $cartId = $cart->getId();

        $existingItem = $this->cartRepository->findCartItem($cartId, $request->productVariantId);
        $newQuantity = $request->quantity;
        if ($existingItem) {
            $newQuantity += $existingItem->getQuantity();
        }

        if ($newQuantity > (int)$variant['stock']) {
            return [
                'success' => false,
                'message' => "Cannot add item. Requested quantity ($newQuantity) exceeds available stock ({$variant['stock']})."
            ];
        }

        if ($existingItem) {
            $success = $this->cartRepository->updateCartItemQuantity($existingItem->getId(), $newQuantity);
        } else {
            $newItem = new CartItem([
                'cartId' => $cartId,
                'productVariantId' => $request->productVariantId,
                'quantity' => $request->quantity,
                'priceAtAdded' => (float)$variant['price']
            ]);
            $success = $this->cartRepository->addCartItem($newItem);
        }

        if ($success) {
            $this->recalculateCartTotal($cartId);
            return ['success' => true, 'message' => 'Item added to cart successfully.'];
        }

        return ['success' => false, 'message' => 'Failed to add item to cart.'];
    }

    public function updateCartItem(int $userId, int $cartItemId, UpdateCartItemDtoRequest $request): array
    {
        $err = $request->validate();
        if ($err) {
            return ['success' => false, 'message' => $err];
        }

        $cartItem = $this->cartRepository->findCartItemById($cartItemId);
        if (!$cartItem) {
            return ['success' => false, 'message' => 'Cart item not found.'];
        }

        $cart = $this->cartRepository->findCartByUserId($userId);
        if (!$cart || $cartItem->getCartId() !== $cart->getId()) {
            return ['success' => false, 'message' => 'Unauthorized or cart mismatch.'];
        }

        $variant = $this->cartRepository->getVariantStockAndPrice($cartItem->getProductVariantId());
        if (!$variant) {
            return ['success' => false, 'message' => 'Product variant not found.'];
        }

        if ($request->quantity > (int)$variant['stock']) {
            return [
                'success' => false,
                'message' => "Cannot update quantity. Requested quantity ({$request->quantity}) exceeds available stock ({$variant['stock']})."
            ];
        }

        $success = $this->cartRepository->updateCartItemQuantity($cartItemId, $request->quantity);
        if ($success) {
            $this->recalculateCartTotal($cart->getId());
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
        if (!$cart || $cartItem->getCartId() !== $cart->getId()) {
            return ['success' => false, 'message' => 'Unauthorized or cart mismatch.'];
        }

        $success = $this->cartRepository->deleteCartItem($cartItemId);
        if ($success) {
            $this->recalculateCartTotal($cart->getId());
            return ['success' => true, 'message' => 'Item removed from cart.'];
        }

        return ['success' => false, 'message' => 'Failed to remove item from cart.'];
    }

    private function recalculateCartTotal(int $cartId): void
    {
        $items = $this->cartRepository->getCartItems($cartId);
        $totalCost = 0.00;
        foreach ($items as $item) {
            $totalCost += (float)$item->getCurrentPrice() * (int)$item->getQuantity();
        }
        $this->cartRepository->updateCartTotalCost($cartId, $totalCost);
    }
}
