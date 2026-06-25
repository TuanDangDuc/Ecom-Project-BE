<?php

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-User-Id, X-User-Role");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
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
