Project Structure Documentation
Overview

Dự án được xây dựng theo mô hình Layered Architecture tương tự như Spring Boot, nhưng sử dụng PHP thuần (Pure PHP). Mục tiêu của việc phân chia này là giúp code dễ bảo trì, dễ mở rộng và giúp các thành viên trong nhóm dễ dàng xác định vị trí cần chỉnh sửa khi phát triển tính năng mới.

Luồng xử lý của hệ thống:

Request
    ↓
Router
    ↓
Controller
    ↓
Service
    ↓
Repository Interface
    ↓
Repository Implementation
    ↓
Database

Project Structure
ECOM-LTW/
│
├── public/                         # Điểm khởi động của ứng dụng
│   ├── index.php                   # Front Controller - nhận mọi request
│   └── bootstrap.php               # Khởi tạo dependency (Controller, Service, Repository)
│
├── routes/                         # Định nghĩa các endpoint của hệ thống
│   ├── auth.php                    # Route cho authentication
│   ├── product.php                 # Route cho sản phẩm
│   ├── order.php                   # Route cho đơn hàng
│   └── ...
│
├── app/
│   │
│   ├── controllers/                # Tiếp nhận request từ Router
│   │   ├── AuthController.php
│   │   ├── ProductController.php
│   │   ├── OrderController.php
│   │   └── ...
│   │
│   ├── services/                   # Chứa business logic
│   │   ├── AuthService.php
│   │   ├── ProductService.php
│   │   ├── OrderService.php
│   │   └── ...
│   │
│   ├── repositories/
│   │   ├── UserRepository.php              # Interface
│   │   ├── ProductRepository.php
│   │   ├── OrderRepository.php
│   │   │
│   │   └── impl/                           # Triển khai SQL thực tế
│   │       ├── UserRepositoryImpl.php
│   │       ├── ProductRepositoryImpl.php
│   │       ├── OrderRepositoryImpl.php
│   │       └── ...
│   │
│   ├── models/                     # Đại diện cho dữ liệu trong database
│   │   ├── Users.php
│   │   ├── Product.php
│   │   ├── Order.php
│   │   └── ...
│   │
│   ├── dto/                       # Data Transfer Object
│   │   ├── RegisterDtoRequest.php
│   │   ├── LoginDtoRequest.php
│   │   ├── ProductCreateRequest.php
│   │   └── ...
│   │
│   ├── mappers/                    # Chuyển đổi DTO ↔ Model
│   │   ├── UserMapper.php
│   │   ├── ProductMapper.php
│   │   └── ...
│   │
│   ├── core/                       # Thành phần dùng chung
│   │   ├── Router.php              # Quản lý endpoint
│   │   ├── Database.php            # Kết nối PDO
│   │   ├── Response.php            # Chuẩn hóa JSON response
│   │   └── ...
│   │
│   └── config/                     # Cấu hình hệ thống
│       ├── database.php            # Cấu hình MySQL
│       └── ...
│
├── README.md
└── .gitignore
