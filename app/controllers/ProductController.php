<?php

class ProductController {
    private ProductService $productService;
    
    public function __construct(
        ProductService $productService
    ) {
        $this->productService = $productService;
    }

    public function index(): void
    {
        $filters = [];
        if (isset($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }

        $result = $this->productService->getAllProducts($filters);
        Response::json($result, 200);
    }

    public function show($id): void
    {
        $result = $this->productService->getProductById($id);
        $status = $result['success'] ? 200 : 400;
        Response::json($result, $status);
    }

    public function shopShow($shopId): void
    {
        $result = $this->productService->showShopProduct($shopId);
        
        Response::json($result, 200);
    }

    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data === null) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON input.'
            ], 400);
            return;
        }
        $request = new CreateProductDtoRequest($data);
        $result = $this->productService->createProduct($request);

        $status = $result['success'] ? 200 : 400;
        Response::json($result, $status);
    }

    public function update($id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if ($data === null) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON input.'
            ], 400);
            return;
        }

        $request = new UpdateProductDtoRequest($data);
        $result = $this->productService->updateProduct($id, $request);
        
        $status = $result['success'] ? 200 : 400;
        Response::json($result, $status);
    }

    public function destroy($id): void
    {
        $result = $this->productService->deleteProduct($id);
        
        $status = $result['success'] ? 200 : 400;
        Response::json($result, $status);
    }
}