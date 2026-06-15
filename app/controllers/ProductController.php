<?php

class ProductController {
    private ProductService $productService;
    
    public function __construct(
        ProductService $productService
    ) {
        $this->productService = $productService;
    }

    public function show($id): void
    {
        $result = $this->productService->getProductById($id);
        $status = $result['success'] ? 200 : 404;
        Response::json($result, $status);

    }

    public function index(): void
    {
        $result = $this->productService->getAllProducts();
        
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