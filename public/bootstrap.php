<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Load environment variables
 */
$dotenvPath = dirname(__DIR__);
$dotenv = Dotenv\Dotenv::createUnsafeImmutable($dotenvPath);
$dotenv->safeLoad();

/**
 * Core
 */
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Response.php';
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/AuthHelper.php';
// controller
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/CategoriesController.php';
require_once __DIR__ . '/../app/controllers/ProductTypesController.php';
// service
require_once __DIR__ . '/../app/services/MailService.php';
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
/**
 * Models
 */
require_once __DIR__ . '/../app/models/Users.php';
require_once __DIR__ . '/../app/models/Carts.php';
require_once __DIR__ . '/../app/models/CartItem.php';
require_once __DIR__ . '/../app/models/Orders.php';
require_once __DIR__ . '/../app/models/OrderItem.php';
require_once __DIR__ . '/../app/models/Reviews.php';
require_once __DIR__ . '/../app/models/ReviewImage.php';

/**
 * DTO Requests
 */
require_once __DIR__ . '/../app/dto/request/RegisterDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/LoginDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/ForgotPasswordDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/ResetPasswordDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/VerifyOtpDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/AddToCartDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/UpdateCartItemDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/CheckoutDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/UpdateOrderStatusDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/CreateReviewDtoRequest.php';

/**
 * Mappers
 */
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

/**
 * Enums
 */
require_once __DIR__ . '/../app/enum/Role.php';
require_once __DIR__ . '/../app/enum/AccountStatus.php';

/**
 * Models
 */
require_once __DIR__ . '/../app/models/Users.php';

/**
 * DTO Requests
 */
require_once __DIR__ . '/../app/dto/request/RegisterDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/LoginDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/ForgotPasswordDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/ResetPasswordDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/VerifyOtpDtoRequest.php';

/**
 * Mappers
 */
require_once __DIR__ . '/../app/mappers/UserMapper.php';

/**
 * Repository Interfaces
 */
require_once __DIR__ . '/../app/repositories/IUserRepository.php';
require_once __DIR__ . '/../app/repositories/ICategoriesRepository.php';
require_once __DIR__ . '/../app/repositories/ICartRepository.php';
require_once __DIR__ . '/../app/repositories/IOrderRepository.php';
require_once __DIR__ . '/../app/repositories/IReviewRepository.php';

/**
 * Repository Implementations
 */
require_once __DIR__ . '/../app/repositories/impl/UserRepository.php';
require_once __DIR__ . '/../app/repositories/impl/CategoriesRepository.php';
require_once __DIR__ . '/../app/repositories/impl/CartRepository.php';
require_once __DIR__ . '/../app/repositories/impl/OrderRepository.php';
require_once __DIR__ . '/../app/repositories/impl/ReviewRepository.php';

/**
 * Services
 */
require_once __DIR__ . '/../app/services/MailService.php';
require_once __DIR__ . '/../app/services/AuthService.php';
require_once __DIR__ . '/../app/services/CategoriesService.php';
require_once __DIR__ . '/../app/services/CartService.php';
require_once __DIR__ . '/../app/services/OrderService.php';
require_once __DIR__ . '/../app/services/ReviewService.php';

/**
 * Controllers
 */
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/CategoriesController.php';
require_once __DIR__ . '/../app/controllers/CartController.php';
require_once __DIR__ . '/../app/controllers/OrderController.php';
require_once __DIR__ . '/../app/controllers/ReviewController.php';

/**
 * Database connection
 */
$db = Database::connection();

/**
 * Auth
 */
$userRepository = new UserRepository($db);
$authService = new AuthService($userRepository);
$authController = new AuthController($authService);

//Categories

$categoriesRepository = new CategoriesRepository($db);
$categoriesService = new CategoriesService($categoriesRepository);
$categoriesController = new CategoriesController($categoriesService);

//Cart
$cartRepository = new CartRepository($db);
$cartService = new CartService($cartRepository);
$cartController = new CartController($cartService);

//Order
$orderRepository = new OrderRepository($db);
$orderService = new OrderService($orderRepository, $cartRepository);
$orderController = new OrderController($orderService);

//Review
$reviewRepository = new ReviewRepository($db);
$reviewService = new ReviewService($reviewRepository);
$reviewController = new ReviewController($reviewService);
