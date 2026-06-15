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
require_once __DIR__ . '/../app/controllers/AddressController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/CartController.php';
require_once __DIR__ . '/../app/controllers/CategoriesController.php';
require_once __DIR__ . '/../app/controllers/OrderController.php';
require_once __DIR__ . '/../app/controllers/ProductController.php';
require_once __DIR__ . '/../app/controllers/ProductImageController.php';
require_once __DIR__ . '/../app/controllers/ProductTypesController.php';
require_once __DIR__ . '/../app/controllers/ReviewController.php';
require_once __DIR__ . '/../app/controllers/ShopController.php';
require_once __DIR__ . '/../app/controllers/UserController.php';
require_once __DIR__ . '/../app/controllers/VariantController.php';

// service
require_once __DIR__ . '/../app/services/AddressService.php';
require_once __DIR__ . '/../app/services/AuthService.php'; //Mail required
require_once __DIR__ . '/../app/services/CartService.php';
require_once __DIR__ . '/../app/services/CategoriesService.php';
require_once __DIR__ . '/../app/services/MailService.php';

require_once __DIR__ . '/../app/services/OrderService.php';
require_once __DIR__ . '/../app/services/ProductImageService.php';
require_once __DIR__ . '/../app/services/ProductService.php';
require_once __DIR__ . '/../app/services/ProductTypesService.php';
require_once __DIR__ . '/../app/services/ReviewService.php';
require_once __DIR__ . '/../app/services/ShopService.php';
require_once __DIR__ . '/../app/services/UserService.php';
require_once __DIR__ . '/../app/services/VariantService.php';

// Repository Interfaces
require_once __DIR__ . '/../app/repositories/IAddressRepository.php';
require_once __DIR__ . '/../app/repositories/ICartRepository.php';
require_once __DIR__ . '/../app/repositories/ICategoriesRepository.php';
require_once __DIR__ . '/../app/repositories/IOrderRepository.php';
require_once __DIR__ . '/../app/repositories/IProductImageRepository.php';
require_once __DIR__ . '/../app/repositories/IProductRepository.php';
require_once __DIR__ . '/../app/repositories/IProductTypesRepository.php';
require_once __DIR__ . '/../app/repositories/IReviewRepository.php';
require_once __DIR__ . '/../app/repositories/IShopRepository.php';
require_once __DIR__ . '/../app/repositories/IUserRepository.php';
require_once __DIR__ . '/../app/repositories/IVariantRepository.php';

// Repository Implementations
require_once __DIR__ . '/../app/repositories/impl/AddressRepository.php';
require_once __DIR__ . '/../app/repositories/impl/CartRepository.php';
require_once __DIR__ . '/../app/repositories/impl/CategoriesRepository.php';
require_once __DIR__ . '/../app/repositories/impl/OrderRepository.php';
require_once __DIR__ . '/../app/repositories/impl/ProductImageRepository.php';
require_once __DIR__ . '/../app/repositories/impl/ProductRepository.php';
require_once __DIR__ . '/../app/repositories/impl/ProductTypesRepository.php';
require_once __DIR__ . '/../app/repositories/impl/ReviewRepository.php';
require_once __DIR__ . '/../app/repositories/impl/ShopRepository.php';
require_once __DIR__ . '/../app/repositories/impl/UserRepository.php';
require_once __DIR__ . '/../app/repositories/impl/VariantRepository.php';

// enums
require_once __DIR__ . '/../app/enum/AccountStatus.php';
require_once __DIR__ . '/../app/enum/AddressType.php';
require_once __DIR__ . '/../app/enum/OrderStatus.php';
require_once __DIR__ . '/../app/enum/Role.php';
require_once __DIR__ . '/../app/enum/Sex.php';
require_once __DIR__ . '/../app/enum/ShopStatus.php';

// mapper
require_once __DIR__ . '/../app/mappers/ProductImageMapper.php';
require_once __DIR__ . '/../app/mappers/ProductMapper.php';
require_once __DIR__ . '/../app/mappers/ProductTypeMapper.php';
require_once __DIR__ . '/../app/mappers/UserMapper.php';
require_once __DIR__ . '/../app/mappers/VariantMapper.php';

// Models
require_once __DIR__ . '/../app/models/Address.php';
require_once __DIR__ . '/../app/models/CartItem.php';
require_once __DIR__ . '/../app/models/Carts.php';
require_once __DIR__ . '/../app/models/Category.php';
require_once __DIR__ . '/../app/models/OrderItem.php';
require_once __DIR__ . '/../app/models/Orders.php';
require_once __DIR__ . '/../app/models/Product.php';
require_once __DIR__ . '/../app/models/ProductImages.php';
require_once __DIR__ . '/../app/models/ProductType.php';
require_once __DIR__ . '/../app/models/ProductVariants.php';
require_once __DIR__ . '/../app/models/ReviewImage.php';
require_once __DIR__ . '/../app/models/Reviews.php';
require_once __DIR__ . '/../app/models/Shop.php';
require_once __DIR__ . '/../app/models/Users.php';

// DTO Requests
require_once __DIR__ . '/../app/dto/request/AddToCartDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/CheckoutDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/CreateAddressDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/CreateCategoryDTORequest.php'; // change name DTO -> Dto
require_once __DIR__ . '/../app/dto/request/CreateProductDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/CreateProductImageDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/CreateProductTypeDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/CreateReviewDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/CreateShopDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/CreateVariantDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/ForgotPasswordDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/LoginDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/ModifyUserDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/RegisterDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/ResetPasswordDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/UpdateCartItemDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/UpdateOrderStatusDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/UpdateProductDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/UpdateShopDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/UpdateVariantDtoRequest.php';
require_once __DIR__ . '/../app/dto/request/VerifyOtpDtoRequest.php';


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

// regis route cho products
$productRepository = new ProductRepository($db);
$productService = new ProductService($productRepository);
$productsController = new ProductController($productService);

$variantRepository = new VariantRepository($db);
$variantService = new VariantService($variantRepository);
$variantController = new VariantController($variantService);

$productImageRepository = new ProductImageRepository($db);
$productImageService = new ProductImageService($productImageRepository);
$productImageController = new ProductImageController($productImageService);

/**
 * Auth
 */
$userRepository = new UserRepository($db);
$userService = new UserService($userRepository);
$userController = new UserController($userService);
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

//Shop
$shopRepository = new ShopRepository($db);
$shopService = new ShopService($shopRepository);
$shopController = new ShopController($shopService);

//Address
$addressRepository = new AddressRepository($db);
$addressService = new AddressService($addressRepository);
$addressController = new AddressController($addressService);
