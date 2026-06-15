<?php
require 'app/repositories/IProductRepository.php';
require 'app/repositories/impl/ProductRepository.php';

$pdo = new PDO('sqlite::memory:');
$pdo->exec('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, typeId INTEGER, thumbnailUrl TEXT, basePrice REAL, categoryId INTEGER, ratingAverage REAL, shopId INTEGER)');

$repo = new ProductRepository($pdo);
$result = $repo->create([
    'name' => 'Test Product',
    'typeId' => 1,
    'thumbnailUrl' => '/img.jpg',
    'basePrice' => 12.5,
    'categoryId' => 2,
    'ratingAverage' => 4.5,
    'shopId' => 7,
]);

var_export($result);

echo PHP_EOL;
var_export(require 'config/redisConf.php');
