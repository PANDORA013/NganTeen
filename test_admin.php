<?php
// Test admin access
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== TESTING ADMIN CREDENTIALS ===\n";

$admin = User::where('email', 'admin@nganteen.com')->first();

if ($admin) {
    echo "✅ Admin user found!\n";
    echo "Email: " . $admin->email . "\n";
    echo "Name: " . $admin->name . "\n";
    echo "Role: " . $admin->role . "\n";
    echo "Created: " . $admin->created_at . "\n";
    
    // Test password
    if (Hash::check('Admin123!@#', $admin->password)) {
        echo "✅ Password correct!\n";
    } else {
        echo "❌ Password incorrect!\n";
    }
} else {
    echo "❌ Admin user not found!\n";
}

echo "\n=== ADMIN LOGIN CREDENTIALS ===\n";
echo "Email: admin@nganteen.com\n";
echo "Password: Admin123!@#\n";
echo "URL: http://localhost:8000/admin/dashboard\n";
echo "==================================\n";
?>
