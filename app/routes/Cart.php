<?php

$router->get('/api/cart', [$cartController, 'index']);
$router->post('/api/cart/items', [$cartController, 'store']);
$router->put('/api/cart/items/{id}', [$cartController, 'update']);
$router->delete('/api/cart/items/{id}', [$cartController, 'delete']);
