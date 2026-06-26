<?php

$router->post('/api/orders', [$orderController, 'store']);
$router->get('/api/orders', [$orderController, 'index']);

$router->get('/api/orders/{id}', [$orderController, 'show']);
$router->put('/api/orders/{id}/status', [$orderController, 'updateStatus']);

$router->put('/api/order-item/{id}/status', [$orderController, 'updateOrderItemStatus']);
