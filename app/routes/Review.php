<?php

$router->post('/api/reviews', [$reviewController, 'store']);
$router->get('/api/products/{id}/reviews', [$reviewController, 'showProductReviews']);
$router->post('/api/reviews/{id}/images', [$reviewController, 'uploadImages']);
$router->delete('/api/review-images/{id}', [$reviewController, 'deleteImage']);
$router->put('/api/reviews/{id}/reply', [$reviewController, 'replyToReview']);
