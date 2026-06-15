<?php

class CategoriesController {
    private CategoriesService $categoriesService;
    
    public function __construct(
        CategoriesService $categoriesService
    ) {
        $this->categoriesService = $categoriesService;
    }

    public function index(): void
    {
        $result = $this->categoriesService->getAllCategories();
        
        Response::json($result);
    }

    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $name = trim($data['name'] ?? '');

        // validate
        if ($name === '') {
            Response::json([
                'message' => 'Name is required'
            ], 400);
            return;
        }

        $result = $this->categoriesService->createCategory($name);
        
        $status = $result['success'] ? 200 : 400;
        Response::json($result, $status);
    }
}