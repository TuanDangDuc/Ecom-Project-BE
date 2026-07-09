<?php
use Predis\Client;

return [
    'scheme' => 'tcp',
    'host' => $_ENV['REDIS_HOST'] ?? getenv('REDIS_HOST') ?: 'redis',
    'port' => (int)($_ENV['REDIS_PORT'] ?? getenv('REDIS_PORT') ?: 6379),
];