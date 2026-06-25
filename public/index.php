<?php

$allowed_origins = [
    'http://localhost:8000',
    'https://ecom.anhchuno.id.vn',
    'http://localhost:5173'
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE, PATCH");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
    exit(0);
}

require_once __DIR__ . '/bootstrap.php';

$router = new Router();

require_once __DIR__ . '/../app/routes/Auth.php';
require_once __DIR__ . '/../app/routes/Categories.php';
require_once __DIR__ . '/../app/routes/ProductTypes.php';
require_once __DIR__ . '/../app/routes/Product.php';
require_once __DIR__ . '/../app/routes/Variant.php';
require_once __DIR__ . '/../app/routes/ProductImage.php';
// New routes
require_once __DIR__ . '/../app/routes/Users.php';

require_once __DIR__ . '/../app/routes/Cart.php';
require_once __DIR__ . '/../app/routes/Order.php';
require_once __DIR__ . '/../app/routes/Review.php';
require_once __DIR__ . '/../app/routes/Shop.php';
require_once __DIR__ . '/../app/routes/Address.php';

$router->dispatch();
