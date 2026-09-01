<?php

namespace Tests\Feature;

use App\Models\GroceryItem;
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
            ->assertSee('data-ajax-add-to-order="true"');
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
}
