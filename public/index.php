<?php

require_once __DIR__ . '/bootstrap.php';

$router = new Router();

require_once __DIR__ . '/../app/routes/auth.php';
require_once __DIR__ . '/../app/routes/categories.php';
require_once __DIR__ . '/../app/routes/productTypes.php';
require_once __DIR__ . '/../app/routes/product.php';
require_once __DIR__ . '/../app/routes/variant.php';require_once __DIR__ . '/../app/routes/ProductImage.php';
// New routes
require_once __DIR__ . '/../app/routes/Cart.php';
require_once __DIR__ . '/../app/routes/Order.php';
require_once __DIR__ . '/../app/routes/Review.php';

$router->dispatch();
