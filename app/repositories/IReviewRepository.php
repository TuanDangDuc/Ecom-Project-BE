<?php

interface IReviewRepository
{
    public function createReview(array $reviewData): int;
    public function findReviewById(int $reviewId): ?array;
    public function findReviewByOrderItemId(int $orderItemId): ?array;
    public function findProductReviews(int $productId): array;
    public function addReviewImage(int $reviewId, string $url, int $imageOrder): bool;
    public function deleteReviewImage(int $reviewImageId): bool;
    public function findReviewImageById(int $reviewImageId): ?array;
    public function getReviewImages(int $reviewId): array;
    public function getOrderItemDetails(int $orderItemId): ?array;
    public function updateProductRatingAverage(int $productId): bool;
}
