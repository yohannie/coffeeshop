# ☕ Coffee Shop Web Application

A modern, full-featured web application for managing a coffee shop, built with Laravel and Tailwind CSS.

## Features

### 🛍️ Customer Features
- User authentication and registration
- Browse coffee products by categories (Hot Coffee, Cold Coffee, Pastries)
- Beautiful product catalog with images and descriptions
- Shopping cart functionality
- Real-time order tracking
- Order history
- Responsive, mobile-friendly design

### 👨‍💼 Admin Features
- Secure admin dashboard
- Product management (Add, Edit, Delete)
  - Upload product images
  - Set prices and stock levels
  - Manage product categories
- Order management
  - View and process orders
  - Update order status
  - Track delivery status
- User management
  - Manage customer accounts
  - Admin role assignment
- Sales reporting
  - Daily and monthly sales reports
  - Top-selling products
  - Recent orders tracking

### 💅 Design Features
- Modern, clean interface
- Coffee-themed color scheme
- Glass-effect design elements
- Responsive layout for all devices
- Interactive UI components
- Real-time updates

## Technical Stack

- **Backend:** Laravel (PHP Framework)
- **Frontend:** 
  - Tailwind CSS for styling
  - Alpine.js for interactivity
  - jQuery for AJAX requests
- **Database:** MySQL
- **Authentication:** Laravel's built-in auth
- **File Storage:** Laravel's storage system

## Security Features

- CSRF protection
- Secure password hashing
- Role-based access control
- Form validation
- Secure file upload handling

## Getting Started

1. Clone the repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env` and configure your database
4. Generate application key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Start the development server: `php artisan serve`

## Contributing

Feel free to submit issues and enhancement requests!
