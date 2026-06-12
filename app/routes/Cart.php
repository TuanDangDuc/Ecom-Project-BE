<?php

$router->get('/api/cart', [$cartController, 'index']);
$router->post('/api/cart/items', [$cartController, 'store']);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('#^/api/cart/items/([a-zA-Z0-9_]+)$#', $path, $matches)) {
    $id = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $router->put($path, function() use ($cartController, $id) {
            $cartController->update($id);
        });
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $router->delete($path, function() use ($cartController, $id) {
            $cartController->delete($id);
        });
    }
}
