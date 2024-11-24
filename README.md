# Book Management API

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About This Project

This project is a test implementation for library system in Laravel. It demonstrates how to manage books and categories effectively by roles, ensuring secure and structured access to resources within a Laravel application.

## Installation Instructions

Follow these steps to set up and run the project:

### Prerequisites
- PHP 8.1 or later
- Composer
- MySQL or any database supported by Laravel
- Node.js and NPM (optional, if front-end assets need to be compiled)

### Step 1: Clone the Repository and Set Up the Environment
```bash
git clone 
cd 
composer install
cp .env.example .env
```

Update the `.env` file with your database credentials:
```env
DB_DATABASE=library
```

### Step 2: Create the Database and Run Migrations
Create a database named library in your database system:
```sql
CREATE DATABASE library;
```

Then run the migrations and seeders:
```bash
php artisan migrate --seed
```

### Step 3: Generate the Application Key
```bash
php artisan key:generate
```

### Step 4: Run the Application
```bash
php artisan serve
```
Visit the application at http://127.0.0.1:8000.

### Testing the Application
The project includes test import file Real_Books_Import.xlsx and a postman collection file in the database folder for testing.

## License
This project is open-sourced software licensed under the MIT license.
```
