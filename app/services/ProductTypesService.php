<?php

class ProductTypesService {
    private IProductTypesRepository $productTypesRepository;

    public function __construct(
        IProductTypesRepository $productTypesRepository
    ) {
        $this->ProductTypesRepository = $productTypesRepository;
    }

    public function getAllProductTypes(): array
    {
        return $this->ProductTypesRepository->getAll();
    }

    public function createProductType(CreateProductTypeDtoRequest $request): array
    {
        $err = $request->validate();

        if ($err) 
            return ['success' => false, 'message' => $err];

        if ($this->ProductTypesRepository->checkExistsByName($request->name))
            return ['success' => false, 'message' => 'Product type name already exists.'];
        
        $productType = ProductTypeMapper::CreateProductTypeDtoRequestToProductType($request);
        $saveResult = $this->ProductTypesRepository->create($productType);
        
        if ($saveResult) 
            return ['success' => true, 'message' => 'Product type created successfully.'];
        else 
            return ['success' => false, 'message' => 'Failed to create product type.'];
    }
}