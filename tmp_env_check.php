<?php
require 'vendor/autoload.php';
Dotenv\Dotenv::createImmutable(getcwd())->safeLoad();
var_export([
    'getenv' => getenv('MAIL_FROM'),
    '_ENV' => ($_ENV['MAIL_FROM'] ?? null),
    '_SERVER' => ($_SERVER['MAIL_FROM'] ?? null),
]);
