<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->safeLoad();

$config = require 'config/redisConf.php';
var_dump($config);
try {
    $redis = new Predis\Client($config);
    $redis->set('test', '123');
    echo "Redis set success: " . $redis->get('test') . "\n";
} catch (\Exception $e) {
    echo "Redis error: " . $e->getMessage() . "\n";
}
