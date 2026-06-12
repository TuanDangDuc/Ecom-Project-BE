<?php
// controller
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/CategoriesController.php';
require_once __DIR__ . '/../app/controllers/ProductTypesController.php';
// service
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/services/CategoriesService.php';
require_once __DIR__ . '/../app/services/ProductTypesService.php';
// repo
require_once __DIR__ . '/../app/repositories/IUserRepository.php';
require_once __DIR__ . '/../app/repositories/ICategoriesRepository.php';
require_once __DIR__ . '/../app/repositories/IProductTypesRepository.php';
require_once __DIR__ . '/../app/repositories/impl/UserRepository.php';
require_once __DIR__ . '/../app/repositories/impl/CategoriesRepository.php';
require_once __DIR__ . '/../app/repositories/impl/ProductTypesRepository.php';

// enums
require_once __DIR__ . '/../app/enum/Role.php';
require_once __DIR__ . '/../app/enum/AccountStatus.php';

// mapper
require_once __DIR__ . '/../app/mappers/UserMapper.php';
require_once __DIR__ . '/../app/mappers/ProductTypeMapper.php';

// model
require_once __DIR__ . '/../app/models/Users.php';
require_once __DIR__ . '/../app/models/ProductType.php';

// request
require_once __DIR__ . '/../app/dto/request/RegisterDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/CreateProductTypeDtoRequest.php';
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

// regis route cho product types
$productTypesRepository = new ProductTypesRepository($db);
$productTypesService = new ProductTypesService($productTypesRepository);
$productTypesController = new ProductTypesController($productTypesService);
// ==========================================
// REGISTER CART, ORDER, AND REVIEW DEPENDENCIES
// ==========================================
// Auth Helper

// Repositories Interfaces
require_once __DIR__ . '/../app/repositories/ICartRepository.php';
require_once __DIR__ . '/../app/repositories/IOrderRepository.php';
require_once __DIR__ . '/../app/repositories/IReviewRepository.php';

// Repositories Implementations
require_once __DIR__ . '/../app/repositories/impl/CartRepository.php';
require_once __DIR__ . '/../app/repositories/impl/OrderRepository.php';
require_once __DIR__ . '/../app/repositories/impl/ReviewRepository.php';

// Services
require_once __DIR__ . '/../app/services/CartService.php';
require_once __DIR__ . '/../app/services/OrderService.php';
require_once __DIR__ . '/../app/services/ReviewService.php';

// Controllers
require_once __DIR__ . '/../app/controllers/CartController.php';
require_once __DIR__ . '/../app/controllers/OrderController.php';
require_once __DIR__ . '/../app/controllers/ReviewController.php';

// Instantiations
$cartRepository = new CartRepository($db);
$cartService = new CartService($cartRepository);
$cartController = new CartController($cartService);

$orderRepository = new OrderRepository($db);
$orderService = new OrderService($orderRepository, $cartRepository);
$orderController = new OrderController($orderService);

$reviewRepository = new ReviewRepository($db);
$reviewService = new ReviewService($reviewRepository);
$reviewController = new ReviewController($reviewService);
