<?php

$router->post(
    '/api/variants/{id}/images',
    [$productImageController, 'store']
);

$router->delete(
    '/api/product-images/{id}',
        [$productImageController, 'destroy']
);
