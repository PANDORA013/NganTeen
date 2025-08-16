<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Cart;
use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Testing\File;
use Tests\TestCase;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_pembeli_dapat_menghapus_akun_dengan_password_benar()
    {
        // Arrange
        $user = User::factory()->create([
            'role' => 'pembeli',
            'password' => bcrypt('password123'),
        ]);

        // Buat beberapa item cart untuk user
        Cart::factory()->count(3)->create(['user_id' => $user->id]);

        // Act
        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password123',
            ]);

        // Assert
        $response->assertRedirect('/');
        $this->assertNull(User::find($user->id)); // User sudah terhapus
        $this->assertEquals(0, Cart::where('user_id', $user->id)->count()); // Cart items terhapus
        $this->assertGuest(); // User ter-logout
    }

    public function test_pembeli_tidak_dapat_menghapus_akun_dengan_password_salah()
    {
        // Arrange
        $user = User::factory()->create([
            'role' => 'pembeli',
            'password' => bcrypt('password123'),
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password-salah',
            ]);

        // Assert
        $response->assertSessionHasErrorsIn('userDeletion', 'password');
        $this->assertNotNull(User::find($user->id)); // User masih ada
        $this->assertAuthenticated(); // User masih login
    }

    public function test_penjual_dapat_menghapus_akun_beserta_menu()
    {
        Storage::fake('public');

        // Arrange
        $user = User::factory()->create([
            'role' => 'penjual',
            'password' => bcrypt('password123'),
        ]);

        // Buat menu dengan gambar
        $menu = Menu::factory()->create([
            'user_id' => $user->id,
            'gambar' => 'menus/test-image.jpg'
        ]);

        // Simulasi file gambar
        Storage::disk('public')->put('menus/test-image.jpg', 'fake-image-content');

        // Act
        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password123',
            ]);

        // Assert
        $response->assertRedirect('/');
        $this->assertNull(User::find($user->id)); // User sudah terhapus
        $this->assertNull(Menu::find($menu->id)); // Menu sudah terhapus
        $this->assertFalse(Storage::disk('public')->exists('menus/test-image.jpg')); // Gambar sudah terhapus
        $this->assertGuest(); // User ter-logout
    }

    public function test_hapus_akun_membersihkan_foto_profil_dan_qris()
    {
        Storage::fake('public');

        // Arrange
        $user = User::factory()->create([
            'role' => 'penjual',
            'password' => bcrypt('password123'),
            'profile_photo' => 'profile_photos/test-profile.jpg',
            'qris_image' => 'qris/test-qris.jpg'
        ]);

        // Simulasi file foto profil dan QRIS
        Storage::disk('public')->put('profile_photos/test-profile.jpg', 'fake-profile-content');
        Storage::disk('public')->put('qris/test-qris.jpg', 'fake-qris-content');

        // Act
        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password123',
            ]);

        // Assert
        $response->assertRedirect('/');
        $this->assertNull(User::find($user->id)); // User sudah terhapus
        $this->assertFalse(Storage::disk('public')->exists('profile_photos/test-profile.jpg')); // Foto profil terhapus
        $this->assertFalse(Storage::disk('public')->exists('qris/test-qris.jpg')); // QRIS terhapus
    }

    public function test_hapus_akun_menampilkan_pesan_sukses()
    {
        // Arrange
        $user = User::factory()->create([
            'role' => 'pembeli',
            'password' => bcrypt('password123'),
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password123',
            ]);

        // Assert
        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'Akun Anda telah berhasil dihapus. Terima kasih telah menggunakan layanan kami.');
    }

    public function test_route_hapus_akun_memerlukan_autentikasi()
    {
        // Act
        $response = $this->delete('/profile', [
            'password' => 'password123',
        ]);

        // Assert
        $response->assertRedirect('/login');
    }

    public function test_password_kosong_tidak_diizinkan()
    {
        // Arrange
        $user = User::factory()->create([
            'role' => 'pembeli',
            'password' => bcrypt('password123'),
        ]);

        // Act
        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => '',
            ]);

        // Assert
        $response->assertSessionHasErrorsIn('userDeletion', 'password');
        $this->assertNotNull(User::find($user->id)); // User masih ada
    }
}
