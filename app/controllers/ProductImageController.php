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
        $userId = AuthHelper::getCurrentUserId();
        if ($userId === null) {
            Response::json(['success' => false, 'message' => 'Unauthorized.'], 401);
            return;
        }

        $files = $_FILES ?? [];
        $result = $this->productImageService->uploadImages((int)$variantId, $files);
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
