
# Laravel 11 Project with JWT Auth and Redis Caching

This is a Laravel 11 project whic include user authentication with JWT, Redis caching for product listing, and product management. There are admin and regular users.

## Prerequisites

Before starting need to installed on machine:
1. **PHP**: Version 8.2 or above
2. **Composer**: PHP’s dependency manager
4. **Database**: MySQL or PostgreSQL
6. **Git**: For version control

## Installation

### Step 1: Clone the Repository

```bash
git clone https://github.com/moniruzzaman17/JWT-RESTapi.git
cd JWT-RESTapi
```
### Step 2: Install Dependencies

Run the following command to install all the PHP dependencies:

```bash
composer install
```

## Environment Setup

### Step 3: Set Up Environment Variables

1. Duplicate the `.env.example` file and rename it to `.env`:

    ```bash
    cp .env.example .env
    ```

2. Update the following values in the `.env` file:

    - **Database Configuration**:
      ```plaintext
      DB_CONNECTION=mysql
      DB_HOST=127.0.0.1
      DB_PORT=3306
      DB_DATABASE=database_name
      DB_USERNAME=root
      DB_PASSWORD=dbpassword
      ```

    - **JWT Secret**:
      Generate a JWT secret using the following command:
      ```bash
      php artisan jwt:secret
      ```

## Database Migration and Seeding

### Step 4: Migrate and Seed the Database

To set up database schema and seed data, run the following commands:

```bash
php artisan migrate
php artisan db:seed
```

This will create two users:
- **Admin**: `admin@gmail.com` with password `123456`
- **Regular User**: `user@gmail.com` with password `123456`

It will also create 5 sample products.

## API Endpoints

Here is a list of available API endpoints. Authentication via JWT token is required for certain routes.

### Public Endpoints

1. **Register**: 
   - **POST** `/auth/register`
   - Sample Request Body:
     ```json
     {
         "name": "Test User",
         "email": "user@gmail.com",
         "password": "123456",
         "password_confirmation": "123456"
     }
     ```

2. **Login**: 
   - **POST** `/auth/login`
   - Sample Request Body:
     ```json
     {
         "email": "user@gmail.com",
         "password": "123456"
     }
     ```

3. **Product Listing (Cached)**: 
   - **GET** `/products`

### Authenticated User Endpoints

These endpoints require a valid JWT token in the `Authorization: Bearer <token>` header.

1. **Get Authenticated User Info**:
   - **POST** `/me`

2. **Place an Order**: 
   - **POST** `/place-order`
   - Sample Request Body:
     ```json
     {
         "items": [
             {"product_id": 1, "quantity": 2},
             {"product_id": 2, "quantity": 1}
         ]
     }
     ```

3. **View Order History**:
   - **GET** `/order-history`

### Admin Endpoints

Admin-only endpoints also require a valid JWT token in the `Authorization: Bearer <token>` header.

1. **Create a New Product**: 
   - **POST** `/products`
   - Sample Request Body:
     ```json
     {
         "name": "New Product",
         "price": 45.99,
         "stock": 100
     }
     ```

2. **Update an Existing Product**: 
   - **POST** `/products/{product_id}`
   - Sample Request Body:
     ```json
     {
         "name": "Updated Product",
         "price": 55.99,
         "stock": 200
     }
     ```

## Technologies Used

- **Laravel 11**: PHP framework for backend development
- **JWT Authentication**: JSON Web Tokens for securing API endpoints
- **Redis**: For caching
- **Predis**: Redis client for PHP to interact with Redis
- **MySQL**: Relational database for storing users, products, and orders
- **Composer**: Dependency manager for PHP
- **PHP**: PHP version 8.2 or above
