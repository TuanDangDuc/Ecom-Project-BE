<?php

interface ICartRepository
{
    function findCartByUserId(int $userId): ?Carts;
    function createCart(Carts $cart): int;
    function getCartItems(int $cartId): array;
    function findCartItem(int $cartId, int $productVariantId): ?CartItem;
    function findCartItemById(int $cartItemId): ?CartItem;
    function addCartItem(CartItem $item): bool;
    function updateCartItemQuantity(int $cartItemId, int $quantity): bool;
    function deleteCartItem(int $cartItemId): bool;
    function updateCartTotalCost(int $cartId, float $totalCost): bool;
    function getVariantStockAndPrice(int $productVariantId): ?array;
}
