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

        $data = json_decode(file_get_contents('php://input'), true);
        if ($data === null) {
            Response::json(['success' => false, 'message' => 'Invalid JSON input.'], 400);
            return;
        }

        $result = $this->orderService->checkout($userId, $data);
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

        $data = json_decode(file_get_contents('php://input'), true);
        $status = isset($data['status']) ? trim($data['status']) : '';

        if (empty($status)) {
            Response::json(['success' => false, 'message' => 'status is required.'], 400);
            return;
        }

        $role = AuthHelper::getCurrentUserRole(Database::connection());

        $result = $this->orderService->updateOrderStatus($userId, $role, (int)$id, $status);
        $httpStatus = $result['success'] ? 200 : 400;
        Response::json($result, $httpStatus);
    }
}
