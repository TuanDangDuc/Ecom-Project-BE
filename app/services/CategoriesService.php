<?php

class CategoriesService {
    private ICategoriesRepository $categoriesRepository;

    public function __construct(
        ICategoriesRepository $categoriesRepository
    ) {
        $this->categoriesRepository = $categoriesRepository;
    }

    public function getAllCategories(): array
    {
        return $this->categoriesRepository->getAll();
    }

    public function createCategory(string $name): array
    {
        if($this->categoriesRepository->checkExistsByName($name)) {
            return ['success' => false, 'message' => 'Category name already exists.'];
        }   
        
        $storeResult = $this->categoriesRepository->create($name);

        if($storeResult) {
            return ['success' => true, 'message' => 'Category created successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to create category.'];
        }
    }
}