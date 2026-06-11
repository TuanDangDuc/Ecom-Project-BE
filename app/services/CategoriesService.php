<?php

class CategoriesService {
    private ICategoriesRepository $categoriesRepository;

    public function __construct(
        ICategoriesRepository $categoriesRepository
    ) {
        $this->CategoriesRepository = $categoriesRepository;
    }

    public function getAllCategories(): array
    {
        return $this->CategoriesRepository->getAll();
    }

    public function createCategory(string $name): array
    {
        if($this->CategoriesRepository->checkExistsByName($name)) {
            return ['success' => false, 'message' => 'Category name already exists.'];
        }   
        
        $storeResult = $this->CategoriesRepository->create($name);

        if($storeResult) {
            return ['success' => true, 'message' => 'Category created successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to create category.'];
        }
    }
}