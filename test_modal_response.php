<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

echo "--- TESTING DETAIL TAWARAN RENDERING AFTER MODAL RESPONSE FIX --- \n";

$user = User::where('role', 'pelamar')->where('status', '!=', 1)->first();
Auth::login($user);

$url = '/pelamar/kandidat/tawaran/pt-areakerja-teknologi/qa-tester-test';
$req = Request::create($url, 'GET');
$req->setLaravelSession(app('session.store'));
$res = $kernel->handle($req);

echo "Status {$url}: " . $res->getStatusCode() . "\n";
if ($res->getStatusCode() !== 200) {
    preg_match('/<title>(.*?)<\/title>/s', $res->getContent(), $mTitle);
    echo "Title: " . ($mTitle[1] ?? 'N/A') . "\n";
} else {
    echo "✅ GET {$url} rendered successfully with modal response fixes! (Length: " . strlen($res->getContent()) . " bytes)\n";
}
