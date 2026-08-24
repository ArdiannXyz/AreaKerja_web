<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "--- CHECKING EMAIL SUBSCRIBERS TABLE ---\n";
$hasTable = Schema::hasTable('email_subscribers');
echo "Table 'email_subscribers' exists: " . ($hasTable ? "YES" : "NO") . "\n";

if ($hasTable) {
    $columns = Schema::getColumnListing('email_subscribers');
    echo "Columns: " . implode(', ', $columns) . "\n";
}
