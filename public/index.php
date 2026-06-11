<?php

require_once __DIR__ . '/bootstrap.php';

$router = new Router();

require_once __DIR__ . '/../app/routes/auth.php';
require_once __DIR__ . '/../app/routes/categories.php';

$router->dispatch();
