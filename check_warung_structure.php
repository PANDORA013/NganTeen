<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $columns = DB::select('DESCRIBE warungs');
    echo "Struktur tabel warungs:\n";
    foreach ($columns as $column) {
        echo "Field: " . $column->Field . 
             " | Type: " . $column->Type . 
             " | Null: " . $column->Null . 
             " | Default: " . $column->Default . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
