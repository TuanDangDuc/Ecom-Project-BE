<?php

class CartController
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): void
    {
        $userId = AuthHelper::getCurrentUserId();
        if ($userId === null) {
            Response::json(['success' => false, 'message' => 'Unauthorized.'], 401);
            return;
        }

        $result = $this->cartService->getCart($userId);
        Response::json($result, 200);
    }

    public function store(): void
    {
        $userId = AuthHelper::getCurrentUserId();
        if ($userId === null) {
            Response::json(['success' => false, 'message' => 'Unauthorized.'], 401);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $productVariantId = isset($data['productVariantId']) ? (int)$data['productVariantId'] : null;
        $quantity = isset($data['quantity']) ? (int)$data['quantity'] : 1;

        if ($productVariantId === null) {
            Response::json(['success' => false, 'message' => 'productVariantId is required.'], 400);
            return;
        }

        $result = $this->cartService->addToCart($userId, $productVariantId, $quantity);
        $status = $result['success'] ? 200 : 400;
        Response::json($result, $status);
    }

    public function update(string $id): void
    {
        $userId = AuthHelper::getCurrentUserId();
        if ($userId === null) {
            Response::json(['success' => false, 'message' => 'Unauthorized.'], 401);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $quantity = isset($data['quantity']) ? (int)$data['quantity'] : null;

        if ($quantity === null) {
            Response::json(['success' => false, 'message' => 'quantity is required.'], 400);
            return;
        }

        $result = $this->cartService->updateCartItem($userId, (int)$id, $quantity);
        $status = $result['success'] ? 200 : 400;
        Response::json($result, $status);
    }

    public function delete(string $id): void
    {
        $userId = AuthHelper::getCurrentUserId();
        if ($userId === null) {
            Response::json(['success' => false, 'message' => 'Unauthorized.'], 401);
            return;
        }

        $result = $this->cartService->removeFromCart($userId, (int)$id);
        $status = $result['success'] ? 200 : 400;
        Response::json($result, $status);
    }
}
