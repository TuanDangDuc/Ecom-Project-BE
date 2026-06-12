<?php

$router->post('/api/orders', [$orderController, 'store']);
$router->get('/api/orders', [$orderController, 'index']);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('#^/api/orders/([a-zA-Z0-9_]+)$#', $path, $matches)) {
    $id = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $router->get($path, function() use ($orderController, $id) {
            $orderController->show($id);
        });
    }
} elseif (preg_match('#^/api/orders/([a-zA-Z0-9_]+)/status$#', $path, $matches)) {
    $id = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $router->put($path, function() use ($orderController, $id) {
            $orderController->updateStatus($id);
        });
    }
}
