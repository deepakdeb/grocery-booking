# Grocery Booking System — Senior Architecture & Implementation Plan

## 1. Architectural Overview & Design Patterns
To ensure high maintainability, testability, and separation of concerns, this system implements a **Multi-Layer Architecture**:
* **Presentation Layer:** Laravel Controllers (API responses / AJAX handlers) + Blade Views with Alpine.js/jQuery for seamless client-side interactions.
* **Service Layer:** Encapsulates business logic (e.g., order placement, stock management, transaction safety). Controllers remain thin.
* **Repository Pattern:** Abstracts data access. Interfaces bound via Service Providers to Eloquent implementations.
* **Security & Concurrency:** JWT authentication via `tymon/jwt-auth`, strict role-based middleware guards, and database-level pessimistic locking (`lockForUpdate()`) to prevent race conditions during high-concurrency stock deductions.

---

## 2. Database Schema & Relationships
* **`roles`**: id, name (`admin`, `user`), timestamps
* **`users`**: id, name, email, password, role_id, timestamps (Foreign key: `role_id` -> `roles.id`)
* **`grocery_items`**: id, name, description, price, stock_quantity, timestamps
* **`orders`**: id, user_id, total_amount, status (`pending`, `completed`, `cancelled`), timestamps (Foreign key: `user_id` -> `users.id`)
* **`order_items`**: id, order_id, grocery_item_id, quantity, price, timestamps

---

## 3. Step-by-Step Implementation Roadmap for GitHub Copilot

### Phase 1: Environment & Core Setup
1. **Initialize Project:** Set up Laravel 12, configure MySQL inside `docker-compose.yml` (App container + MySQL container).
2. **Authentication:** Install `tymon/jwt-auth`, publish config, and configure `User` model to implement `JWTSubject`.
3. **Roles & Migration:** Create migrations for all tables with proper foreign keys and indexes. Seed initial admin and user accounts.

### Phase 2: Repository & Service Layers
1. **Interfaces:** Create `GroceryRepositoryInterface` and `OrderRepositoryInterface` inside `app/Repositories/Contracts/`.
2. **Eloquent Implementations:** Create `GroceryRepository` and `OrderRepository` inside `app/Repositories/Eloquent/`.
3. **Service Provider:** Bind interfaces to implementations in `AppServiceProvider` or a dedicated `RepositoryServiceProvider`.
4. **Services:** Implement `GroceryService` and `OrderService` (injecting repositories and handling atomic database transactions via `DB::transaction`).

### Phase 3: API & Middleware
1. **Middleware:** Create `CheckRole` middleware to intercept requests based on user roles (`admin`, `user`) using JWT claims.
2. **Controllers:** Implement `Admin\GroceryController` and `User\OrderController` relying strictly on the Service layer.

### Phase 4: Frontend (Blade + AJAX + Localization)
1. **Localization:** Set up localization files (`lang/en/messages.php`, `lang/bn/messages.php`) and a language switcher endpoint/middleware.
2. **Blade Views:** Create layouts with Bootstrap. Implement a dynamic AJAX-powered checkout flow where users pick items, view real-time stock updates, and submit orders without full page reloads.

---