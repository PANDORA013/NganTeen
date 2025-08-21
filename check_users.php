<?php
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = \App\Models\User::select('id', 'name', 'email', 'role')->get();

echo "Users in database:\n";
echo "==================\n";

foreach($users as $user) {
    echo $user->id . " - " . $user->name . " (" . $user->email . ") - Role: " . $user->role . "\n";
}

if ($users->count() == 0) {
    echo "No users found in database.\n";
}
