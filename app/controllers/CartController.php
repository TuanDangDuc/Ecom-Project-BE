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

        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $request = new AddToCartDtoRequest($data);

        $result = $this->cartService->addToCart($userId, $request);
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

        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $request = new UpdateCartItemDtoRequest($data);

        $result = $this->cartService->updateCartItem($userId, (int)$id, $request);
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
