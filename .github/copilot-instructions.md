# Laravel Architectural Rules & Coding Standards

You are an expert Laravel developer and senior software engineer. When writing, refactoring, or generating code for this repository, you MUST strictly adhere to the following architectural patterns and rules. Never cut corners.

## 1. Mandatory Architectural Layers
- **Controllers Must Be Thin:** Controllers should only handle HTTP requests, validate input using Form Requests, invoke a Service, and return an API response or Blade view. No business logic, no Eloquent queries, and no DB transactions inside controllers.
- **Service Layer (Business Logic):** All core business logic, calculations, and orchestrations must live inside Service classes (e.g., `App\Services\OrderService`). 
- **Repository Pattern (Data Access):** 
  - Never call Eloquent models directly inside Services or Controllers. 
  - Always inject a Repository Interface (e.g., `GroceryRepositoryInterface`) into the Service constructor.
  - Implement the interface using an Eloquent repository class (e.g., `EloquentGroceryRepository`).
  - Ensure all interfaces are properly bound in a Service Provider.

## 2. Concurrency & Database Safety
- **Transactions:** Any multi-step database operation (like creating an order and deducting stock) MUST be wrapped in a database transaction (`DB::transaction(...)`).
- **Pessimistic Locking:** When checking or deducting inventory stock under concurrent requests, always use database-level locking (`->lockForUpdate()`) inside the repository method to prevent overselling.

## 3. Security & Validation
- **Authentication:** Use JWT authentication (`tymon/jwt-auth`). Ensure middleware guards enforce role-based access control (Admin vs. User) at the route level, never inside controller actions.
- **Form Requests:** Never validate inputs inline in controllers. Always use dedicated Laravel `FormRequest` classes.

## 4. Code Quality & Style
- Strict typing (`declare(strict_types=1);`) must be used in all new PHP files.
- Document methods with clear PHPDoc blocks specifying types and thrown exceptions.
- Never write placeholder comments like `// TODO: implement later` or `// add logic here`—always write complete, working code.