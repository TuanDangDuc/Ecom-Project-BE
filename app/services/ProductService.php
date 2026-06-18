<?php

class ProductService
{
    private IProductRepository $productRepository;
    private IShopRepository $shopRepository;

    public function __construct(
        IProductRepository $productRepository,
        IShopRepository $shopRepository
        ) {
        $this->productRepository = $productRepository;
        $this->shopRepository = $shopRepository;
    }

    public function getAllProducts(): array
    {
        return $this->productRepository->findAll();
    }

    public function getProductById(int $id): array
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            return ['success' => false, 'message' => 'Product not found.'];
        }

        return ['success' => true, 'data' => $product];
    }

    public function showShopProduct(int $shopId): array
    {
        if (!$this->shopRepository->findById($shopId)){
            return['success' => false, 'message' => 'Shop not found.'];
        }

        return $this->productRepository->showShopProduct($shopId);
    }

    public function createProduct(CreateProductDtoRequest $request): array
    {
        if (!$this->shopRepository->findById($request->shopId))
            return ['success' => false, 'message' => 'Shop id dont valid.'];

        $product = ProductMapper::CreateProductDtoRequestToProduct($request);
        $saveResult = $this->productRepository->create($product);

        if ($saveResult) {
            return ['success' => true, 'message' => 'Product created successfully.', 'data' => $saveResult];
        }

        return ['success' => false, 'message' => 'Failed to create product.'];
    }

    public function updateProduct(int $id, UpdateProductDtoRequest $request): array
    {
        $existingProduct = $this->productRepository->findById($id);

        if (!$existingProduct) {
            return ['success' => false, 'message' => 'Product not found.'];
        }

        $product = ProductMapper::UpdateProductDtoRequestToProduct($request);
        $saveResult = $this->productRepository->update($id, $product);

        if ($saveResult) {
            return ['success' => true, 'message' => 'Product updated successfully.', 'data' => $saveResult];
        }

        return ['success' => false, 'message' => 'Failed to update product.'];
    }

    public function deleteProduct(int $id): array
    {
        $deleted = $this->productRepository->delete($id);

        if ($deleted) {
            return ['success' => true, 'message' => 'Product deleted successfully.'];
        }

        return ['success' => false, 'message' => 'Failed to delete product.'];
    }
}