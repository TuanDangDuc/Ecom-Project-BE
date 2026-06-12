<?php

class OrderController
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(): void
    {
        $userId = AuthHelper::getCurrentUserId();
        if ($userId === null) {
            Response::json(['success' => false, 'message' => 'Unauthorized.'], 401);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $request = new CheckoutDtoRequest($data);

        $result = $this->orderService->checkout($userId, $request);
        $status = $result['success'] ? 200 : 400;
        Response::json($result, $status);
    }

    public function index(): void
    {
        $userId = AuthHelper::getCurrentUserId();
        if ($userId === null) {
            Response::json(['success' => false, 'message' => 'Unauthorized.'], 401);
            return;
        }

        $result = $this->orderService->getUserOrders($userId);
        Response::json($result, 200);
    }

    public function show(string $id): void
    {
        $userId = AuthHelper::getCurrentUserId();
        if ($userId === null) {
            Response::json(['success' => false, 'message' => 'Unauthorized.'], 401);
            return;
        }

        $result = $this->orderService->getOrderDetail($userId, (int)$id);
        $status = $result['success'] ? 200 : 404;
        Response::json($result, $status);
    }

    public function updateStatus(string $id): void
    {
        $userId = AuthHelper::getCurrentUserId();
        if ($userId === null) {
            Response::json(['success' => false, 'message' => 'Unauthorized.'], 401);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $request = new UpdateOrderStatusDtoRequest($data);

        $role = AuthHelper::getCurrentUserRole(Database::connection());

        $result = $this->orderService->updateOrderStatus($userId, $role, (int)$id, $request);
        $httpStatus = $result['success'] ? 200 : 400;
        Response::json($result, $httpStatus);
    }
}
