<?php

class ProductTypesController {
    private ProductTypesService $productTypesService;
    
    public function __construct(
        ProductTypesService $productTypesService
    ) {
        $this->productTypesService = $productTypesService;
    }

    public function index(): void
    {
        $result = $this->productTypesService->getAllProductTypes();
        
        Response::json($result);
    }

    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        // validate
        if ($data === null) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON input.'
            ], 400);
            return;
        }

        $request = new CreateProductTypeDtoRequest($data);
        $result = $this->productTypesService->createProductType($request);

        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function update($id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data === null) {
            Response::json(['success' => false, 'message' => 'Invalid JSON input.'], 400);
            return;
        }

        $request = new UpdateProductTypeDtoRequest($data);
        $result = $this->productTypesService->updateProductType((int)$id, $request);

        $status = $result['success'] ? 200 : 400;
        Response::json($result, $status);
    }

    public function destroy($id): void
    {
        $result = $this->productTypesService->deleteProductType((int)$id);
        
        $status = $result['success'] ? 200 : 400;
        Response::json($result, $status);
    }
}