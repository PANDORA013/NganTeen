<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Menu;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test cart page loads correctly after refactoring
     */
    public function test_cart_page_loads_with_items()
    {
        // Create a test user (pembeli)
        $user = User::factory()->create(['role' => 'pembeli']);
        
        // Create a test menu
        $menu = Menu::factory()->create([
            'nama_menu' => 'Test Menu',
            'harga' => 10000,
            'stok' => 10,
            'nama_warung' => 'Test Warung'
        ]);
        
        // Add item to cart
        Cart::create([
            'user_id' => $user->id,
            'menu_id' => $menu->id,
            'jumlah' => 2
        ]);

        $response = $this->actingAs($user)
                         ->get('/pembeli/cart');

        $response->assertStatus(200)
                 ->assertSee('Keranjang Belanja')
                 ->assertSee('Test Menu')
                 ->assertSee('Test Warung')
                 ->assertSee('quantity-form')
                 ->assertSee('delete-form');
    }
    
    /**
     * Test empty cart page
     */
    public function test_empty_cart_page()
    {
        $user = User::factory()->create(['role' => 'pembeli']);
        
        $response = $this->actingAs($user)
                         ->get('/pembeli/cart');

        $response->assertStatus(200)
                 ->assertSee('Keranjang Belanja Kosong')
                 ->assertSee('Jelajahi Menu');
    }

    /**
     * Test cart update functionality
     */
    public function test_cart_update_functionality()
    {
        $user = User::factory()->create(['role' => 'pembeli']);
        $menu = Menu::factory()->create(['stok' => 10]);
        
        $cartItem = Cart::create([
            'user_id' => $user->id,
            'menu_id' => $menu->id,
            'jumlah' => 2
        ]);

        $response = $this->actingAs($user)
                         ->put("/pembeli/cart/{$cartItem->id}", [
                             'jumlah' => 3
                         ]);

        $response->assertRedirect(route('pembeli.cart.index'));
        $this->assertEquals(3, $cartItem->fresh()->jumlah);
    }

    /**
     * Test cart delete functionality
     */
    public function test_cart_delete_functionality()
    {
        $user = User::factory()->create(['role' => 'pembeli']);
        $menu = Menu::factory()->create();
        
        $cartItem = Cart::create([
            'user_id' => $user->id,
            'menu_id' => $menu->id,
            'jumlah' => 2
        ]);

        $response = $this->actingAs($user)
                         ->delete("/pembeli/cart/{$cartItem->id}");

        $response->assertRedirect(route('pembeli.cart.index'));
        $this->assertDatabaseMissing('carts', ['id' => $cartItem->id]);
    }
}
