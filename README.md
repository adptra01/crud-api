# INTERVIEW & CHALLENGE TEST from PT. Steradian Data Optima

## Requirements

- PHP version 8.1 or higher
- Composer
- Laravel Framework 10.x
- Required PHP extensions and composer packages as listed in `composer.json`:
  - guzzlehttp/guzzle
  - laravel/sanctum
  - infyomlabs/adminlte-templates
  - infyomlabs/laravel-generator
  - darkaonline/l5-swagger
  - infyomlabs/swagger-generator

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd <repository-directory>
   ```

2. Install PHP dependencies using Composer:
   ```bash
   composer install
   ```

3. Copy the example environment file and configure your environment variables:
   ```bash
   cp .env.example .env
   ```

4. Generate the application key:
   ```bash
   php artisan key:generate
   ```

5. Run database migrations:
   ```bash
   php artisan migrate
   ```

6. (Optional) Seed the database:
   ```bash
   php artisan db:seed
   ```

7. Serve the application locally:
   ```bash
   php artisan serve
   ```

## API Documentation Usage

This project uses Swagger UI for API documentation, powered by the `darkaonline/l5-swagger` package.

- Access the API documentation interface at:
  ```
  http://localhost:8000/api/documentation
  ```
  (Adjust the host and port if different.)

- The Swagger JSON documentation file is located at:
  ```
  storage/api-docs/api-docs.json
  ```

- API routes are defined in `routes/api.php` and include resources for `cars` and `orders`.

- Authentication for API routes uses Laravel Sanctum.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
