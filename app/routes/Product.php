<?php

$router->get(
    '/api/products',
    [$productsController, 'index']
);

$router ->get(
    '/api/product/{id}',
    [$productsController, 'show']
);

$router ->get(
    '/api/categoryProduct/{categoryid}',
    [$productsController, 'categoryShow']
);

$router ->get(
    '/api/shopProduct/{shopid}',
    [$productsController, 'shopShow']
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


