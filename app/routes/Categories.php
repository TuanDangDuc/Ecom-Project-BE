<?php 

$router->get(
    '/api/categories',
    [$categoriesController, 'index']
);

$router->post(
    '/api/categories',
    [$categoriesController, 'store']
);