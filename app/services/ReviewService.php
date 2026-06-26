<?php

class ReviewService
{
    private IReviewRepository $reviewRepository;

    public function __construct(IReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function addReview(int $userId, CreateReviewDtoRequest $request): array
    {
        $err = $request->validate();
        if ($err) {
            return ['success' => false, 'message' => $err];
        }

        $item = $this->reviewRepository->getOrderItemDetails($request->orderItemId);
        if (!$item) {
            return ['success' => false, 'message' => 'Order item not found.'];
        }

        if ((int)$item['userId'] !== $userId) {
            return ['success' => false, 'message' => 'Unauthorized. You cannot review items you did not purchase.'];
        }

        if ($item['orderStatus'] !== 'COMPLETED') {
            return ['success' => false, 'message' => 'You can only review items from completed orders.'];
        }

        $existing = $this->reviewRepository->findReviewByOrderItemId($request->orderItemId);
        if ($existing) {
            return ['success' => false, 'message' => 'This order item has already been reviewed.'];
        }

        $review = new Reviews([
            'orderItemId' => $request->orderItemId,
            'productVariantId' => (int)$item['productVariantId'],
            'userId' => $userId,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'shopReply' => null
        ]);

        $reviewId = $this->reviewRepository->createReview($review);

        if ($reviewId > 0) {
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
        
        $reviewsArray = [];
        foreach ($reviews as $review) {
            $reviewArr = $review->toArray();
            
            $images = $this->reviewRepository->getReviewImages($review->getId());
            $imagesArray = [];
            foreach ($images as $img) {
                $imagesArray[] = $img->toArray();
            }
            
            $reviewArr['images'] = $imagesArray;
            $reviewsArray[] = $reviewArr;
        }

        return ['success' => true, 'reviews' => $reviewsArray];
    }

    public function uploadReviewImages(int $userId, int $reviewId, array $files): array
    {
        $review = $this->reviewRepository->findReviewById($reviewId);
        if (!$review) {
            return ['success' => false, 'message' => 'Review not found.'];
        }

        if ($review->getUserId() !== $userId) {
            return ['success' => false, 'message' => 'Unauthorized. You do not own this review.'];
        }

        $uploadDir = __DIR__ . '/../../public/uploads/reviews/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadedUrls = [];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

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
                continue;
            }

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (empty($ext)) {
                $ext = $file['type'] === 'image/png' ? 'png' : ($file['type'] === 'image/webp' ? 'webp' : 'jpg');
            }
            $filename = md5(uniqid() . microtime()) . '.' . $ext;
            $destPath = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $destPath)) {
                $url = '/uploads/reviews/' . $filename;
                
                $image = new ReviewImage([
                    'reviewId' => $reviewId,
                    'imageUrl' => $url,
                    'imageOrder' => $imageOrder++
                ]);
                
                $this->reviewRepository->addReviewImage($image);
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

        $review = $this->reviewRepository->findReviewById($image->getReviewId());
        if (!$review || $review->getUserId() !== $userId) {
            return ['success' => false, 'message' => 'Unauthorized action.'];
        }

        $filePath = __DIR__ . '/../../public' . $image->getImageUrl();
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $success = $this->reviewRepository->deleteReviewImage($imageId);
        if ($success) {
            return ['success' => true, 'message' => 'Review image deleted.'];
        }

        return ['success' => false, 'message' => 'Failed to delete review image.'];
    }

    public function replyToReview(int $userId, string $role, int $reviewId, string $reply): array
    {
        if ($role !== 'ADMIN' && $role !== 'SELLER') {
            return ['success' => false, 'message' => 'Unauthorized. Only sellers or admins can reply to reviews.'];
        }

        $review = $this->reviewRepository->findReviewById($reviewId);
        if (!$review) {
            return ['success' => false, 'message' => 'Review not found.'];
        }

        if (trim($reply) === '') {
            return ['success' => false, 'message' => 'Reply cannot be empty.'];
        }

        $success = $this->reviewRepository->updateShopReply($reviewId, trim($reply));
        if ($success) {
            return ['success' => true, 'message' => 'Reply added successfully.'];
        }

        return ['success' => false, 'message' => 'Failed to add reply.'];
    }
}
