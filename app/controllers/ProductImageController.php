<?php

class ProductImageController{
    private ProductImageService $productImageService;

    public function __construct(
        ProductImageService $productImageService
    ) {
        $this->productImageService = $productImageService;
    }

    public function store($variantId): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data === null) {
            Response::json(['success' => false, 'message' => 'Invalid JSON input.'], 400);
            return;
        }

        $request = new CreateProductImageDtoRequest($data);
        $result = $this->productImageService->createImage($variantId, $request);
        $status = $result['success'] ? 200 : 400;

        Response::json($result, $status);
    }

    public function destroy($id): void
    {
        $result = $this->productImageService->deleteImage($id);
        $status = $result['success'] ? 200 : 400;

        Response::json($result, $status);
    }
}
