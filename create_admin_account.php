<?php

require_once 'vendor/autoload.php';

// Initialize Laravel app
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== ADMIN ACCOUNT CHECKER & CREATOR ===\n\n";

// Check if admin exists
$admin = User::where('email', 'admin@nganteen.com')->first();

if ($admin) {
    echo "✅ Admin account found:\n";
    echo "   ID: {$admin->id}\n";
    echo "   Name: {$admin->name}\n";
    echo "   Email: {$admin->email}\n";
    echo "   Role: {$admin->role}\n";
    echo "   Created: {$admin->created_at}\n\n";
    
    // Check if password needs update
    if (!Hash::check('Admin123!@#', $admin->password)) {
        echo "🔄 Updating admin password...\n";
        $admin->password = Hash::make('Admin123!@#');
        $admin->save();
        echo "✅ Password updated successfully!\n\n";
    } else {
        echo "✅ Password is correct!\n\n";
    }
} else {
    echo "❌ Admin account not found. Creating new admin account...\n";
    
    $admin = User::create([
        'name' => 'Administrator',
        'email' => 'admin@nganteen.com',
        'password' => Hash::make('Admin123!@#'),
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);
    
    echo "✅ Admin account created successfully!\n";
    echo "   ID: {$admin->id}\n";
    echo "   Name: {$admin->name}\n";
    echo "   Email: {$admin->email}\n";
    echo "   Role: {$admin->role}\n\n";
}

// Show all admin users
echo "=== ALL ADMIN USERS ===\n";
$admins = User::where('role', 'admin')->get();
if ($admins->count() > 0) {
    foreach ($admins as $user) {
        echo "• {$user->name} ({$user->email}) - ID: {$user->id}\n";
    }
} else {
    echo "No admin users found.\n";
}

echo "\n=== LOGIN CREDENTIALS ===\n";
echo "Email: admin@nganteen.com\n";
echo "Password: Admin123!@#\n";
echo "URL: http://localhost:8000/login\n\n";

echo "✅ Admin account setup complete!\n";
