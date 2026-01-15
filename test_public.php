<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
echo "Public path: " . public_path() . "\n";
echo "Base path: " . base_path() . "\n";
echo "App path: " . app_path() . "\n";
