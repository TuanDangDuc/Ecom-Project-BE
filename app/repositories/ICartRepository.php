<?php

interface ICartRepository
{
    public function findCartByUserId(int $userId): ?array;
    public function createCart(int $userId): int;
    public function getCartItems(int $cartId): array;
    public function findCartItem(int $cartId, int $productVariantId): ?array;
    public function findCartItemById(int $cartItemId): ?array;
    public function addCartItem(int $cartId, int $productVariantId, int $quantity, float $price): bool;
    public function updateCartItemQuantity(int $cartItemId, int $quantity): bool;
    public function deleteCartItem(int $cartItemId): bool;
    public function updateCartTotalCost(int $cartId, float $totalCost): bool;
    public function getVariantStockAndPrice(int $productVariantId): ?array;
}
