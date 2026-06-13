<?php

class ProductTypesService {
    private IProductTypesRepository $productTypesRepository;

    public function __construct(
        IProductTypesRepository $productTypesRepository
    ) {
        $this->productTypesRepository = $productTypesRepository;
    }

    public function getAllProductTypes(): array
    {
        return $this->productTypesRepository->getAll();
    }

    public function createProductType(CreateProductTypeDtoRequest $request): array
    {
        $err = $request->validate();

        if ($err) 
            return ['success' => false, 'message' => $err];

        if ($this->productTypesRepository->checkExistsByName($request->name))
            return ['success' => false, 'message' => 'Product type name already exists.'];
        
        $productType = ProductTypeMapper::CreateProductTypeDtoRequestToProductType($request);
        $saveResult = $this->productTypesRepository->create($productType);
        
        if ($saveResult) 
            return ['success' => true, 'message' => 'Product type created successfully.'];
        else 
            return ['success' => false, 'message' => 'Failed to create product type.'];
    }
}