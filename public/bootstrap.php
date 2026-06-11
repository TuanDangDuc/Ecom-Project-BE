<?php
// controller
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/CategoriesController.php';
// service
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/services/CategoriesService.php';
// repo
require_once __DIR__ . '/../app/repositories/IUserRepository.php';
require_once __DIR__ . '/../app/repositories/ICategoriesRepository.php';
require_once __DIR__ . '/../app/repositories/impl/UserRepository.php';
require_once __DIR__ . '/../app/repositories/impl/CategoriesRepository.php';

// enums
require_once __DIR__ . '/../app/enum/Role.php';
require_once __DIR__ . '/../app/enum/AccountStatus.php';

// mapper
require_once __DIR__ . '/../app/mappers/UserMapper.php';

// model
require_once __DIR__ . '/../app/models/Users.php';

// request
require_once __DIR__ . '/../app/dto/request/RegisterDtoRequest.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Response.php';
require_once __DIR__ . '/../app/core/Router.php';

$db = Database::connection();

// regis route cho auth
$userRepository = new UserRepository($db);
$authService = new AuthService($userRepository);
$authController = new AuthController($authService);

// regis route cho categories
$categoriesRepository = new CategoriesRepository($db);
$categoriesService = new CategoriesService($categoriesRepository);
$categoriesController = new CategoriesController($categoriesService);