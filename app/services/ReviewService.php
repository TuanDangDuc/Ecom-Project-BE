<?php

class ReviewService
{
    private IReviewRepository $reviewRepository;

    public function __construct(IReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function addReview(int $userId, array $reviewData): array
    {
        $orderItemId = isset($reviewData['orderItemId']) ? (int)$reviewData['orderItemId'] : null;
        $rating = isset($reviewData['rating']) ? (int)$reviewData['rating'] : null;
        $comment = trim($reviewData['comment'] ?? '');

        if ($orderItemId === null || $rating === null) {
            return ['success' => false, 'message' => 'orderItemId and rating are required.'];
        }

        if ($rating < 1 || $rating > 5) {
            return ['success' => false, 'message' => 'Rating must be between 1 and 5.'];
        }

        // Get details of the order item
        $item = $this->reviewRepository->getOrderItemDetails($orderItemId);
        if (!$item) {
            return ['success' => false, 'message' => 'Order item not found.'];
        }

        // Check ownership
        if ((int)$item['userId'] !== $userId) {
            return ['success' => false, 'message' => 'Unauthorized. You cannot review items you did not purchase.'];
        }

        // Check status (order item must be completed/delivered to be reviewed)
        if ($item['orderStatus'] !== 'COMPLETED') {
            return ['success' => false, 'message' => 'You can only review items from completed orders.'];
        }

        // Check if already reviewed
        $existing = $this->reviewRepository->findReviewByOrderItemId($orderItemId);
        if ($existing) {
            return ['success' => false, 'message' => 'This order item has already been reviewed.'];
        }

        // Create review
        $reviewId = $this->reviewRepository->createReview([
            'orderItemId' => $orderItemId,
            'productVariantId' => (int)$item['productVariantId'],
            'userId' => $userId,
            'rating' => $rating,
            'comment' => $comment,
            'shopReply' => null
        ]);

        if ($reviewId > 0) {
            // Update product rating average
            $this->reviewRepository->updateProductRatingAverage((int)$item['productId']);
            return [
                'success' => true,
                'message' => 'Review added successfully.',
                'reviewId' => $reviewId
            ];
        }

        return ['success' => false, 'message' => 'Failed to add review.'];
    }

    public function getProductReviews(int $productId): array
    {
        $reviews = $this->reviewRepository->findProductReviews($productId);
        foreach ($reviews as &$review) {
            $review['images'] = $this->reviewRepository->getReviewImages((int)$review['id']);
        }
        return ['success' => true, 'reviews' => $reviews];
    }

    public function uploadReviewImages(int $userId, int $reviewId, array $files): array
    {
        $review = $this->reviewRepository->findReviewById($reviewId);
        if (!$review) {
            return ['success' => false, 'message' => 'Review not found.'];
        }

        if ((int)$review['userId'] !== $userId) {
            return ['success' => false, 'message' => 'Unauthorized. You do not own this review.'];
        }

        // Target directory: public/uploads/reviews/
        $uploadDir = __DIR__ . '/../../public/uploads/reviews/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadedUrls = [];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        // Normalize $_FILES structure if multiple files uploaded
        $normalizedFiles = [];
        if (isset($files['images'])) {
            $fileData = $files['images'];
            if (is_array($fileData['name'])) {
                for ($i = 0; $i < count($fileData['name']); $i++) {
                    if ($fileData['error'][$i] === UPLOAD_ERR_OK) {
                        $normalizedFiles[] = [
                            'name' => $fileData['name'][$i],
                            'type' => $fileData['type'][$i],
                            'tmp_name' => $fileData['tmp_name'][$i],
                            'size' => $fileData['size'][$i]
                        ];
                    }
                }
            } else {
                if ($fileData['error'] === UPLOAD_ERR_OK) {
                    $normalizedFiles[] = $fileData;
                }
            }
        }

        if (empty($normalizedFiles)) {
            return ['success' => false, 'message' => 'No files uploaded or upload error occurred.'];
        }

        $imageOrder = count($this->reviewRepository->getReviewImages($reviewId));

        foreach ($normalizedFiles as $file) {
            if (!in_array($file['type'], $allowedTypes)) {
                continue; // Skip invalid types
            }

            // Generate unique name
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (empty($ext)) {
                $ext = $file['type'] === 'image/png' ? 'png' : ($file['type'] === 'image/webp' ? 'webp' : 'jpg');
            }
            $filename = md5(uniqid() . microtime()) . '.' . $ext;
            $destPath = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $destPath)) {
                $url = '/uploads/reviews/' . $filename;
                $this->reviewRepository->addReviewImage($reviewId, $url, $imageOrder++);
                $uploadedUrls[] = $url;
            }
        }

        return [
            'success' => true,
            'message' => 'Images uploaded successfully.',
            'urls' => $uploadedUrls
        ];
    }

    public function deleteReviewImage(int $userId, int $imageId): array
    {
        $image = $this->reviewRepository->findReviewImageById($imageId);
        if (!$image) {
            return ['success' => false, 'message' => 'Image not found.'];
        }

        $review = $this->reviewRepository->findReviewById((int)$image['reviewId']);
        if (!$review || (int)$review['userId'] !== $userId) {
            return ['success' => false, 'message' => 'Unauthorized action.'];
        }

        // Delete physical file
        $filePath = __DIR__ . '/../../public' . $image['url'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $success = $this->reviewRepository->deleteReviewImage($imageId);
        if ($success) {
            return ['success' => true, 'message' => 'Review image deleted.'];
        }

        return ['success' => false, 'message' => 'Failed to delete review image.'];
    }
}
