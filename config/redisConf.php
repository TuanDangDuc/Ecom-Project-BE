<?php
use Predis\Client;

$env = static function (string $key, string $default): string {
    $value = $_ENV[$key] ?? getenv($key);
    if ($value === false || $value === null || $value === '') {
        return $default;
    }
    return (string)$value;
};

return [
    'scheme' => 'tcp',
    'host' => $env('REDIS_HOST', '127.0.0.1'),
    'port' => (int)$env('REDIS_PORT', '6379'),
];
