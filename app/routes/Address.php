<?php

$router->post(
    "/api/address",
    [$addressController, 'createAddress']
);

$router->get(
    "/api/address/user",
    [$addressController, 'getAddressesByUserId']
);

$router->put(
    "/api/address",
    [$addressController, 'updateAddress']
);

$router->delete(
    "/api/address",
    [$addressController, 'deleteAddress']
);

$router->get(
    "/api/address/detail",
    [$addressController, 'getAddressById']
);
