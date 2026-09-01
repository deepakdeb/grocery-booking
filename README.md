# Grocery Booking System

A Laravel-based grocery booking application with role-based JWT authentication, admin inventory management, user order placement, stock protection, thin Blade view shells, and English/Bangla localization.

## Features

- Admin manages grocery items through JWT-protected API calls from the Blade UI
- Users browse inventory and checkout through a client-side cart powered by JavaScript and localStorage
- Stock validation prevents overselling before creating orders
- Atomic inventory deduction uses database transactions and row locking
- JWT-based auth and role checks via `auth:api` and the `role` middleware
- Localization remains in the web layer via `/lang/{locale}` route switching
- Docker Compose setup for the app + MySQL

## Tech Stack

- Laravel 12
- PHP 8.2+
- MySQL 8
- JWT via `tymon/jwt-auth`
- Blade templates with JavaScript AJAX clients

## Architecture Notes

This project follows the layered architecture described in the implementation plan, with the web layer kept intentionally thin:

- Presentation layer: Blade templates that load data via JavaScript and JWT from the API
- API layer: REST endpoints for auth, item management, and order creation/history
- Service layer: business rules such as order processing and validation
- Repository layer: data access abstraction for grocery and order operations
- Security: JWT auth and role middleware, with no Sanctum stateful middleware on the API group
- Concurrency safety: `DB::transaction()` and `lockForUpdate()` during stock deduction

All mutations now go through the API, while the web routes serve only page shells and language switching.

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
- `POST /api/logout` (JWT required)
- `POST /api/refresh` (JWT required)

### Admin endpoints

- `GET /api/admin/grocery-items` (JWT + admin role)
- `POST /api/admin/grocery-items` (JWT + admin role)
- `GET /api/admin/grocery-items/{id}` (JWT + admin role)
- `PUT /api/admin/grocery-items/{id}` (JWT + admin role)
- `DELETE /api/admin/grocery-items/{id}` (JWT + admin role)

### User endpoints

- `GET /api/items` (JWT required)
- `POST /api/orders` (JWT required)
- `GET /api/orders` (JWT required)

### Web routes (view-only shells)

- `GET /`
- `GET /login`
- `GET /register`
- `GET /orders`
- `GET /admin`
- `GET /admin/items`
- `GET /admin/items/create`
- `GET /admin/items/{id}/edit`
- `GET /lang/{locale}` where locale is `en` or `bn`

The storefront and admin screens fetch and submit data by reading the JWT from `localStorage` and calling the matching API endpoints.

## Default Accounts

Seeded users:

- Admin: `admin@grocery.test` / `password123`
- User: `user@grocery.test` / `password123`

## Testing

```bash
php artisan test
```

## Notes

The app is intentionally structured so that controllers stay thin, while the business logic is handled by services and repositories. Blade views are now light UI shells that talk to the API using JWT tokens. This keeps the codebase maintainable and allows stock and order logic to be tested reliably and safely.
