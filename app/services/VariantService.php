<?php

class VariantService
{
    private IVariantRepository $variantRepository;
    private IProductRepository $productRepository;

    public function __construct(
        IVariantRepository $variantRepository,
        IProductRepository $productRepository
    ) {
        $this->variantRepository = $variantRepository;
        $this->productRepository = $productRepository;
    }

    public function getVariantsByProductId(int $productId): array
    {
        if(!$this->productRepository->findById($productId))
            return ['success' => false, 'message' => "This product doesn't exists."];

        return [
            'success' => true,
            'data' => $this->variantRepository->findAllByProductId($productId)
        ];
    }

    public function createVariant(int $productId, CreateVariantDtoRequest $request): array
    {
        $err = $request->validate();
        if ($err) {
            return ['success' => false, 'message' => $err];
        }

        $variant = VariantMapper::CreateVariantDtoRequestToVariant($request, $productId);

        $saveResult = $this->variantRepository->create($variant);

        if ($saveResult) {
            return ['success' => true, 'message' => 'Variant created successfully.'];
        }

        return ['success' => false, 'message' => 'Failed to create variant.'];
    }

    public function updateVariant(int $id, UpdateVariantDtoRequest $request): array
    {
        $err = $request->validate();
        if ($err) {
            return ['success' => false, 'message' => $err];
        }

        $variant = $this->variantRepository->findById($id);
        if (!$variant) {
            return ['success' => false, 'message' => 'Variant not found.'];
        }

        $variant = VariantMapper::UpdateVariantDtoRequestToVariant($request, $variant);

        $saveResult = $this->variantRepository->update($id, $variant);

        if ($saveResult) {
            return ['success' => true, 'message' => 'Variant updated successfully.'];
        }

        return ['success' => false, 'message' => 'Failed to update variant.'];
    }

    public function deleteVariant(int $id): array
    {
        $variant = $this->variantRepository->findById($id);
        if (!$variant) {
            return ['success' => false, 'message' => 'Variant not found.'];
        }

        $deleted = $this->variantRepository->delete($id);

        if ($deleted) {
            return ['success' => true, 'message' => 'Variant deleted successfully.'];
        }

        return ['success' => false, 'message' => 'Failed to delete variant.'];
    }
}
