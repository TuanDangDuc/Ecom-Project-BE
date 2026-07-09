<?php
use Predis\Client;

return [
    'scheme' => 'tcp',
    'host' => getenv('REDIS_HOST') ?: 'redis',
    'port' => (int)(getenv('REDIS_PORT') ?: 6379),
];