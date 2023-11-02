<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About The Project
This project is intended to be used as a starter project and can be used as a reference for building APIs with Laravel 10 and Sanctum, the project contains the following features:
- [x] [Laravel 10](https://laravel.com/docs/10.x/releases)
- [x] [Laravel Sanctum](https://laravel.com/docs/10.x/sanctum)
- [x] [Laravel-permission](https://spatie.be/docs/laravel-permission/v5/introduction)
- [x] [Laravel-activitylog](https://spatie.be/docs/laravel-activitylog/v4/introduction)
- [x] [Laravel-medialibrary](https://spatie.be/docs/laravel-medialibrary/v9/introduction)
- [x] [Swagger](https://swagger.io/)

## Getting Started
##### To install the project follow the steps below:
1. Clone the repo
   ```sh
   git clone
    ```
2. Install Composer dependencies
   ```sh
    composer install
    ```
3. copy .env.example to .env
   ```sh
    cp .env.example .env
    ```
4. Create a database and add the database credentials to the .env file by modifying the following lines
   ```sh
    DB_CONNECTION=
    DB_HOST=
    DB_PORT=
    DB_DATABASE=
    DB_USERNAME=
    DB_PASSWORD=
    ```
5. Generate the application key
   ```sh
    php artisan key:generate
    ```
6. Run the migrations and seeders
   ```sh
    php artisan migrate --seed
    ```
   
## Usage
##### To use the project follow the steps below:
1. Run the server
   ```sh
    php artisan serve
    ```
2. Open the following url in your browser to view the swagger documentation
   ```sh
    http://localhost:8000/api/documentation
    ```
3. Use Postman or any other API testing tool to test the APIs
   ```sh
    http://localhost:8000/api
    ```