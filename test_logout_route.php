<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

use Illuminate\Http\Request;

echo "--- TESTING LOGOUT ROUTE (GET & POST) ---\n";

$reqGet = Request::create('/logout', 'GET');
$resGet = $kernel->handle($reqGet);
echo "GET /logout status: " . $resGet->getStatusCode() . " (Redirect Location: " . $resGet->headers->get('Location') . ")\n";

$reqPost = Request::create('/logout', 'POST');
$resPost = $kernel->handle($reqPost);
echo "POST /logout status: " . $resPost->getStatusCode() . " (Redirect Location: " . $resPost->headers->get('Location') . ")\n";

if ($resGet->getStatusCode() === 302 && $resPost->getStatusCode() === 302) {
    echo "✅ LOGOUT TEST PASSED SUCCESSFULLY!\n";
} else {
    echo "❌ LOGOUT TEST FAILED!\n";
}
