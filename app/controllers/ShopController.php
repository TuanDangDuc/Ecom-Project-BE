<?php   

class ShopController {
    private ShopService $shopService;
    public function __construct(
        ShopService $shopService
    ) {
        $this->shopService = $shopService;
    }
    public function createShop(): void {
        
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data === null) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON input.'
            ], 400);
            return;
        }
        $request = new CreateShopDtoRequest($data);
        if ($request->validate() === false) {
            Response::json([
                'success' => false,
                'message' => 'Invalid data.'
            ], 400);
            return;
        }
        $result = $this->shopService->createShop($request);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function updateShop(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data === null) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON input.'
            ], 400);
            return;
        }

        $request = new UpdateShopDtoRequest($data);
        $result = $this->shopService->updateShop($request);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function getShopsByUserId(): void {
        if (!isset($_GET['userId'])) {
            Response::json([
                'success' => false,
                'message' => 'Missing userId parameter.'
            ], 400);
            return;
        }

        $userId = (int)$_GET['userId'];
        $result = $this->shopService->getShopsByUserId($userId);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function getShopById(): void {
        if (!isset($_GET['id'])) {
            Response::json([
                'success' => false,
                'message' => 'Missing id parameter.'
            ], 400);
            return;
        }

        $id = (int)$_GET['id'];
        $result = $this->shopService->getShopById($id);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function updateShopStatus(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data === null || !isset($data['id']) || !isset($data['status'])) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON input or missing id/status.'
            ], 400);
            return;
        }

        $result = $this->shopService->updateShopStatus((int)$data['id'], $data['status']);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function deleteShop(): void {
        if (!isset($_GET['id'])) {
            Response::json([
                'success' => false,
                'message' => 'Missing id parameter.'
            ], 400);
            return;
        }

        $id = (int)$_GET['id'];
        $result = $this->shopService->deleteShop($id);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function updateShopRating(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data === null || !isset($data['id']) || !isset($data['ratingAverage'])) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON input or missing id/ratingAverage.'
            ], 400);
            return;
        }

        $result = $this->shopService->updateShopRating((int)$data['id'], (float)$data['ratingAverage']);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function getAllShops(): void {
        $result = $this->shopService->getAllShops();
        Response::json($result, 200);
    }
}