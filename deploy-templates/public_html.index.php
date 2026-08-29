<?php

// Salin file ini ke public_html/index.php lewat File Manager cPanel.
// Ganti USERNAME di bawah dengan username cPanel kamu, lalu simpan.
// File ini menunjuk ke aplikasi yang ada di luar public_html —
// jadi .env, app/, vendor/ tetap tidak bisa diakses langsung dari browser.

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$appPath = '/home/USERNAME/temanbelajarkaifa';

require $appPath.'/vendor/autoload.php';

$app = require_once $appPath.'/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
