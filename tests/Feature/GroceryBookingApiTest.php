<?php

namespace Tests\Feature;

use App\Models\GroceryItem;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroceryBookingApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_grocery_item(): void
    {
        $admin = User::factory()->create([
            'role_id' => Role::factory()->admin()->create()->id,
        ]);

        $token = auth('api')->login($admin);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/grocery-items', [
                'name' => 'Rice',
                'description' => 'Premium rice',
                'price' => 25.50,
                'stock_quantity' => 10,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Rice')
            ->assertJsonPath('data.stock_quantity', 10);
    }

    public function test_admin_inventory_page_is_available(): void
    {
        $response = $this->get('/admin/items');

        $response->assertOk()
            ->assertSee('Inventory')
            ->assertSee('Add item');
    }

    public function test_customer_store_page_has_ajax_add_to_order_hook(): void
    {
        GroceryItem::factory()->create([
            'name' => 'Milk',
            'price' => 20,
            'stock_quantity' => 5,
        ]);

        $response = $this->get('/orders');

        $response->assertOk()
            ->assertSee('Add to order')
            ->assertSee('data-ajax-add-to-order');
    }

    public function test_web_routes_are_view_only_for_storefront(): void
    {
        $this->get('/orders/add')->assertNotFound();
        $this->get('/orders/checkout')->assertNotFound();
    }

    public function test_user_can_place_order_with_stock_deduction(): void
    {
        $role = Role::factory()->user()->create();
        $user = User::factory()->create(['role_id' => $role->id]);
        $grocery = GroceryItem::factory()->create([
            'name' => 'Milk',
            'price' => 20,
            'stock_quantity' => 5,
        ]);

        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/orders', [
                'items' => [
                    ['grocery_item_id' => $grocery->id, 'quantity' => 2],
                ],
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.total_amount', 40)
            ->assertJsonPath('data.status', 'completed');

        $this->assertDatabaseHas('grocery_items', ['id' => $grocery->id, 'stock_quantity' => 3]);
    }

    public function test_user_cannot_order_more_than_available_stock(): void
    {
        $role = Role::factory()->user()->create();
        $user = User::factory()->create(['role_id' => $role->id]);
        $grocery = GroceryItem::factory()->create([
            'name' => 'Eggs',
            'price' => 12,
            'stock_quantity' => 2,
        ]);

        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/orders', [
                'items' => [
                    ['grocery_item_id' => $grocery->id, 'quantity' => 5],
                ],
            ]);

        $response->assertStatus(422)
            ->assertJsonPath('message', 'Requested quantity exceeds available stock.');
    }

    public function test_grocery_items_list_is_paginated(): void
    {
        $admin = User::factory()->create([
            'role_id' => Role::factory()->admin()->create()->id,
        ]);
        GroceryItem::factory()->count(30)->create();

        $token = auth('api')->login($admin);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/grocery-items?per_page=5&page=2');

        $response->assertOk()
            ->assertJsonStructure(['data', 'meta', 'links'])
            ->assertJsonPath('meta.per_page', 5)
            ->assertJsonPath('meta.current_page', 2)
            ->assertJsonPath('meta.total', 30)
            ->assertJsonPath('meta.last_page', 6)
            ->assertJsonPath('meta.from', 6)
            ->assertJsonPath('meta.to', 10);

        $response->assertJsonCount(5, 'data');
    }

    public function test_grocery_items_list_uses_default_per_page(): void
    {
        $admin = User::factory()->create([
            'role_id' => Role::factory()->admin()->create()->id,
        ]);
        GroceryItem::factory()->count(20)->create();

        $token = auth('api')->login($admin);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/grocery-items');

        $response->assertOk()
            ->assertJsonStructure(['data', 'meta', 'links'])
            ->assertJsonPath('meta.per_page', 15)
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonPath('meta.total', 20)
            ->assertJsonPath('meta.last_page', 2);

        $response->assertJsonCount(15, 'data');
    }

    public function test_order_history_list_is_paginated(): void
    {
        $role = Role::factory()->user()->create();
        $user = User::factory()->create(['role_id' => $role->id]);
        Order::factory()->count(25)->create(['user_id' => $user->id]);

        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/orders?per_page=10&page=1');

        $response->assertOk()
            ->assertJsonStructure(['data', 'meta', 'links'])
            ->assertJsonPath('meta.per_page', 10)
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonPath('meta.total', 25)
            ->assertJsonPath('meta.last_page', 3);

        $response->assertJsonCount(10, 'data');
    }
}
