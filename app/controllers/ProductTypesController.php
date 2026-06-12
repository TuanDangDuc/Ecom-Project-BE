<?php

class ProductTypesController {
    private ProductTypesService $productTypesService;
    
    public function __construct(
        ProductTypesService $productTypesService
    ) {
        $this->ProductTypesService = $productTypesService;
    }

    public function index(): void
    {
        $result = $this->ProductTypesService->getAllProductTypes();
        
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
        $result = $this->ProductTypesService->createProductType($request);

        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }
}