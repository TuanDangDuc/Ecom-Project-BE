<?php

$router->post(
    '/api/auth/register',
    [$authController, 'register']
);

$router->post(
    '/api/auth/login',
    [$authController, 'login']
);