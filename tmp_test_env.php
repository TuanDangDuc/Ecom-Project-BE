<?php
require 'vendor/autoload.php';
Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();
echo getenv('REDIS_HOST') . ':' . getenv('REDIS_PORT') . PHP_EOL;
