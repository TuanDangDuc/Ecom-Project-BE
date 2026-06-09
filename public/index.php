<?php

require_once __DIR__ . '/bootstrap.php';

$router = new Router();

require_once __DIR__ . '/../app/routes/auth.php';

$router->dispatch();
