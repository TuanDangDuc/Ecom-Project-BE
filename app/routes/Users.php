<?php

$router->get(
    '/api/user/getAllUser',
    [$userController, 'getAllUserByPage']
);

$router->get(
    '/api/user/getUserByUsername',
    [$userController, 'getUserByUsername']
);

$router->delete(
    "/api/user/delete",
    [$userController, 'deleteUserByUsername']
);

$router->put(
    "/api/user",
    [$userController, 'updateUser']
);

$router->patch(
    "/api/user/status",
    [$userController, 'updateAccountStatus']
);

$router->patch(
    "/api/user/role",
    [$userController, 'updateRole']
);