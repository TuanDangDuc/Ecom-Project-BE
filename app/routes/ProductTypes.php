<?php

$router->get(
    '/api/product-types',
    [$productTypesController, 'index']
);

$router->post(
    '/api/product-types',
    [$productTypesController, 'store']
);

$router->put(
    '/api/product-types/{id}',
    [$productTypesController, 'update']
);

$router->delete(
    '/api/product-types/{id}',
    [$productTypesController, 'destroy']
);