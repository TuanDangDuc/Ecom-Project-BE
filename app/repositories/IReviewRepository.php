<?php

interface IReviewRepository
{
    public function createReview(Reviews $review): int;
    public function findReviewById(int $reviewId): ?Reviews;
    public function findReviewByOrderItemId(int $orderItemId): ?Reviews;
    public function findProductReviews(int $productId): array;
    public function addReviewImage(ReviewImage $image): bool;
    public function deleteReviewImage(int $reviewImageId): bool;
    public function findReviewImageById(int $reviewImageId): ?ReviewImage;
    public function getReviewImages(int $reviewId): array;
    public function getOrderItemDetails(int $orderItemId): ?array;
    public function updateProductRatingAverage(int $productId): bool;
}
