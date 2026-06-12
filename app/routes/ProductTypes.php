<?php

$router->get(
    '/api/product-types',
    [$productTypesController, 'index']
);

$router->post(
    '/api/product-types',
    [$productTypesController, 'store']
);