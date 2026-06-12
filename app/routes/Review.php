<?php

$router->post('/api/reviews', [$reviewController, 'store']);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('#^/api/products/([a-zA-Z0-9_]+)/reviews$#', $path, $matches)) {
    $productId = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $router->get($path, function() use ($reviewController, $productId) {
            $reviewController->showProductReviews($productId);
        });
    }
} elseif (preg_match('#^/api/reviews/([a-zA-Z0-9_]+)/images$#', $path, $matches)) {
    $reviewId = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $router->post($path, function() use ($reviewController, $reviewId) {
            $reviewController->uploadImages($reviewId);
        });
    }
} elseif (preg_match('#^/api/review-images/([a-zA-Z0-9_]+)$#', $path, $matches)) {
    $imageId = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $router->delete($path, function() use ($reviewController, $imageId) {
            $reviewController->deleteImage($imageId);
        });
    }
}
