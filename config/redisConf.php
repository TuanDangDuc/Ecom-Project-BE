<?php
use Predis\Client;

return [
    'scheme' => 'tcp',
    'host' => $_ENV['REDIS_HOST'],
    'port' => $_ENV['REDIS_PORT']
];