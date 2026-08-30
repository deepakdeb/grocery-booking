# Grocery Booking System

A Laravel-based grocery booking application with role-based JWT authentication, admin inventory management, user order placement, stock protection, Blade/AJAX storefront, and English/Bangla localization.

## Features

- Admin controls grocery item creation, update, and deletion
- User can browse items and place orders
- Stock validation prevents overselling
- Atomic inventory deduction using transactions and row locking
- JWT-based auth and role middleware
- Blade + AJAX storefront and language switcher
- Docker Compose setup for app + MySQL

## Tech Stack

- Laravel 12
- PHP 8.2+
- MySQL 8
- JWT via `tymon/jwt-auth`
- Blade + jQuery AJAX

## Architecture Notes

This project follows the layered architecture described in the implementation plan:

- Presentation layer: controllers and Blade views
- Service layer: business rules such as order processing and validation
- Repository layer: data access abstraction for grocery and order operations
- Security: JWT auth and role middleware
- Concurrency safety: `DB::transaction()` and `lockForUpdate()` during stock deduction

## Local Setup

### 1) Install dependencies

```bash
composer install
npm install
```

### 2) Configure environment

Copy the example file and adjust values as needed:

```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

For local SQLite development, the default `.env.example` is already compatible. If you want MySQL instead, update the database section in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=grocery_booking
DB_USERNAME=root
DB_PASSWORD=
```

### 3) Run migrations and seed data

```bash
php artisan migrate --seed
```

### 4) Start the app

```bash
php artisan serve
```

Open http://localhost:8000

## Docker Setup

A ready-to-use Docker Compose configuration is included.

### Start containers

```bash
docker compose up --build
```

This starts:

- app on http://localhost:8000
- MySQL on port 3306

### Stop containers

```bash
docker compose down
```

## API Endpoints

### Public auth endpoints

- `POST /api/register`
- `POST /api/login`
- `POST /api/logout`
- `POST /api/refresh`

### Admin endpoints

- `GET /api/admin/grocery-items`
- `POST /api/admin/grocery-items`
- `GET /api/admin/grocery-items/{id}`
- `PUT /api/admin/grocery-items/{id}`
- `DELETE /api/admin/grocery-items/{id}`

### User endpoints

- `GET /api/items`
- `POST /api/orders`
- `GET /api/orders`

### Web localization

- `GET /lang/{locale}` where locale is `en` or `bn`

## Default Accounts

Seeded users:

- Admin: `admin@grocery.test` / `password123`
- User: `user@grocery.test` / `password123`

## Testing

```bash
php artisan test
```

## Notes

The app is intentionally structured so that controllers stay thin, while the business logic is handled by services and repositories. This keeps the codebase maintainable and allows stock and order logic to be tested reliably and safely.
