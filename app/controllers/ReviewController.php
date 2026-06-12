<?php

class ReviewController
{
    private ReviewService $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function store(): void
    {
        $userId = AuthHelper::getCurrentUserId();
        if ($userId === null) {
            Response::json(['success' => false, 'message' => 'Unauthorized.'], 401);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if ($data === null) {
            Response::json(['success' => false, 'message' => 'Invalid JSON input.'], 400);
            return;
        }

        $result = $this->reviewService->addReview($userId, $data);
        $status = $result['success'] ? 200 : 400;
        Response::json($result, $status);
    }

    public function showProductReviews(string $productId): void
    {
        $result = $this->reviewService->getProductReviews((int)$productId);
        Response::json($result, 200);
    }

    public function uploadImages(string $id): void
    {
        $userId = AuthHelper::getCurrentUserId();
        if ($userId === null) {
            Response::json(['success' => false, 'message' => 'Unauthorized.'], 401);
            return;
        }

        $result = $this->reviewService->uploadReviewImages($userId, (int)$id, $_FILES);
        $status = $result['success'] ? 200 : 400;
        Response::json($result, $status);
    }

    public function deleteImage(string $id): void
    {
        $userId = AuthHelper::getCurrentUserId();
        if ($userId === null) {
            Response::json(['success' => false, 'message' => 'Unauthorized.'], 401);
            return;
        }

        $result = $this->reviewService->deleteReviewImage($userId, (int)$id);
        $status = $result['success'] ? 200 : 400;
        Response::json($result, $status);
    }
}
