<?php

class CartRepository implements ICartRepository
{
    public function __construct(
        private PDO $db
    ){}

    public function findCartByUserId(int $userId): ?Carts
    {
        $sql = "SELECT * FROM carts WHERE userId = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? new Carts($result) : null;
    }

    public function createCart(Carts $cart): int
    {
        $sql = "INSERT INTO carts (userId, totalCost) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cart->getUserId(), $cart->getTotalCost() ?? 0.00]);
        return (int)$this->db->lastInsertId();
    }

    public function getCartItems(int $cartId): array
    {
        $sql = "SELECT 
                    ci.id AS id,
                    ci.cartId,
                    ci.quantity,
                    ci.price AS priceAtAdded,
                    ci.productVariantId,
                    pv.price AS currentPrice,
                    pv.stock AS currentStock,
                    pv.options AS variantOptions,
                    p.id AS productId,
                    p.name AS productName,
                    p.thumbnailUrl AS productThumbnail
                FROM cartItem ci
                JOIN productVariants pv ON ci.productVariantId = pv.id
                JOIN product p ON pv.productId = p.id
                WHERE ci.cartId = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cartId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $cartItemObjects = [];
        foreach ($items as $item) {
            if (isset($item['variantOptions'])) {
                $item['variantOptions'] = json_decode($item['variantOptions'], true);
            }
            $cartItemObjects[] = new CartItem($item);
        }
        return $cartItemObjects;
    }

    public function findCartItem(int $cartId, int $productVariantId): ?CartItem
    {
        $sql = "SELECT * FROM cartItem WHERE cartId = ? AND productVariantId = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cartId, $productVariantId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? new CartItem($result) : null;
    }

    public function findCartItemById(int $cartItemId): ?CartItem
    {
        $sql = "SELECT * FROM cartItem WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cartItemId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? new CartItem($result) : null;
    }

    public function addCartItem(CartItem $item): bool
    {
        $sql = "INSERT INTO cartItem (cartId, productVariantId, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $item->getCartId(), 
            $item->getProductVariantId(), 
            $item->getQuantity(), 
            $item->getPriceAtAdded()
        ]);
    }

    public function updateCartItemQuantity(int $cartItemId, int $quantity): bool
    {
        $sql = "UPDATE cartItem SET quantity = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$quantity, $cartItemId]);
    }

    public function deleteCartItem(int $cartItemId): bool
    {
        $sql = "DELETE FROM cartItem WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$cartItemId]);
    }

    public function updateCartTotalCost(int $cartId, float $totalCost): bool
    {
        $sql = "UPDATE carts SET totalCost = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$totalCost, $cartId]);
    }

    public function getVariantStockAndPrice(int $productVariantId): ?array
    {
        $sql = "SELECT id, price, stock, productId FROM productVariants WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productVariantId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }
}
