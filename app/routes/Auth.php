<?php

$router->post(
    '/api/auth/register',
    [$authController, 'register']
);