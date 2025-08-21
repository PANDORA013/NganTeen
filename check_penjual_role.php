<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$user = User::where('email', 'thomas@penjual.com')->first();

if ($user) {
    echo "User: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Role: " . $user->role . "\n";
    echo "ID: " . $user->id . "\n";
    echo "Created: " . $user->created_at . "\n";
} else {
    echo "User not found\n";
}
