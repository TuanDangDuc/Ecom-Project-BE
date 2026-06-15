<?php

$router->get(
    '/api/products',
    [$productsController, 'index']
);

$router ->get(
    '/api/product/{id}',
    [$productsController, 'show']
);

$router->post(
    '/api/product',
    [$productsController, 'store']
);

$router ->put(
    '/api/product/{id}',
    [$productsController, 'update']
);

$router ->delete(
    '/api/product/{id}',
    [$productsController, 'destroy']
);

// $router ->get(
//     '/api/product/{shopid}',
//     [$productsController, 'shopShow']
// );

// $router ->get(
//     'api/products',
//     [$productsController, 'index']
// );