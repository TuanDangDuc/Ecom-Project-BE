<?php

$router->post(
    "/api/shop",
    [$shopController, 'createShop']
);

$router->get(
    "/api/shop",
    [$shopController, 'getAllShops']
);

$router->put(
    "/api/shop",
    [$shopController, 'updateShop']
);

$router->get(
    "/api/shop/user",
    [$shopController, 'getShopsByUserId']
);

$router->get(
    "/api/shop/detail",
    [$shopController, 'getShopById']
);

$router->patch(
    "/api/shop/status",
    [$shopController, 'updateShopStatus']
);

$router->delete(
    "/api/shop",
    [$shopController, 'deleteShop']
);

$router->patch(
    "/api/shop/rating",
    [$shopController, 'updateShopRating']
);