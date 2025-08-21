<?php
// Test security - create test user and try admin access
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== TESTING SECURITY ===\n";

// Create test user dengan role penjual
$testUser = User::firstOrCreate(
    ['email' => 'test@test.com'],
    [
        'name' => 'Test User',
        'password' => bcrypt('password'),
        'role' => 'penjual'
    ]
);

echo "✅ Test user created:\n";
echo "Email: " . $testUser->email . "\n";
echo "Role: " . $testUser->role . "\n";

echo "\n=== ADMIN ACCESS TEST ===\n";
echo "1. Login dengan: test@test.com (role: penjual)\n";
echo "2. Coba akses: http://localhost:8000/admin/dashboard\n";
echo "3. Harus mendapat: ERROR 403 - Access Denied\n";
echo "\n=== VALID ADMIN ACCESS ===\n";
echo "Login dengan: admin@nganteen.com (role: admin)\n";
echo "Password: Admin123!@#\n";
echo "URL: http://localhost:8000/admin/dashboard\n";
echo "========================\n";
?>
