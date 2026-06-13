<?php   

class AddressController {
    private AddressService $addressService;
    public function __construct(
        AddressService $addressService
    ) {
        $this->addressService = $addressService;
    }
    
    public function createAddress(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data === null) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON input.'
            ], 400);
            return;
        }

        $request = new CreateAddressDtoRequest($data);
        if ($request->validate() === false) {
            Response::json([
                'success' => false,
                'message' => 'Invalid data.'
            ], 400);
            return;
        }

        $result = $this->addressService->createAddress($request);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function getAddressesByUserId(): void {
        if (!isset($_GET['userId'])) {
            Response::json([
                'success' => false,
                'message' => 'Missing userId parameter.'
            ], 400);
            return;
        }

        $userId = (int)$_GET['userId'];
        $result = $this->addressService->getAddressesByUserId($userId);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function updateAddress(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data === null) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON input.'
            ], 400);
            return;
        }

        $request = new UpdateAddressDtoRequest($data);
        $result = $this->addressService->updateAddress($request);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function deleteAddress(): void {
        if (!isset($_GET['id'])) {
            Response::json([
                'success' => false,
                'message' => 'Missing id parameter.'
            ], 400);
            return;
        }

        $id = (int)$_GET['id'];
        $result = $this->addressService->deleteAddress($id);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function getAddressById(): void {
        if (!isset($_GET['id'])) {
            Response::json([
                'success' => false,
                'message' => 'Missing id parameter.'
            ], 400);
            return;
        }

        $id = (int)$_GET['id'];
        $result = $this->addressService->getAddressById($id);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }
}
