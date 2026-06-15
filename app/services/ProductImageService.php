<?php

class ProductImageService
{
    private IProductImageRepository $productImageRepository;

    public function __construct(
        IProductImageRepository $productImageRepository
    ) {
        $this->productImageRepository = $productImageRepository;
    }

    public function createImage(int $variantId, CreateProductImageDtoRequest $request): array
    {
        $err = $request->validate();
        if ($err) {
            return ['success' => false, 'message' => $err];
        }
        

        $image = ProductImageMapper::CreateProductImageDtoRequestToProductImage($request, $variantId);
        $saveResult = $this->productImageRepository->create($image);

        if ($saveResult) {
            return ['success' => true, 'message' => 'Image added successfully.'];
        }

        return ['success' => false, 'message' => 'Failed to add image.'];
    }

    public function deleteImage(int $id): array
    {
        $deleted = $this->productImageRepository->delete($id);

        if ($deleted) {
            return ['success' => true, 'message' => 'Image deleted successfully.'];
        }

        return ['success' => false, 'message' => 'Failed to delete image.'];
    }
}
