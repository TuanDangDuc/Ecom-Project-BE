<?php

$router->get(
    '/api/products/{productId}/variants',
    [$variantController, 'index']
);

$router->post(
    '/api/products/{productId}/variants',
    [$variantController, 'store']
);

$router->put(
    '/api/variants/{id}',
    [$variantController, 'update']
);

$router->delete(
    '/api/variants/{id}',
    [$variantController, 'destroy']
);
