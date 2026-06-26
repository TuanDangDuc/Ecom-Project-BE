<?php 

$router->get(
    '/api/categories',
    [$categoriesController, 'index']
);

$router->post(
    '/api/categories',
    [$categoriesController, 'store']
);

$router->put(
    '/api/categories/{id}',
    [$categoriesController, 'update']
);

$router->delete(
    '/api/categories/{id}',
    [$categoriesController, 'destroy']
);