<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// -------------------
// Maintenance Mode Check
// -------------------
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// -------------------
// Register Composer Autoloader
// -------------------
require __DIR__.'/vendor/autoload.php';

// -------------------
// Bootstrap Laravel Application
// -------------------
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

// -------------------
// Handle Incoming Request
// -------------------
$app->handleRequest(Request::capture());