<?php

use Illuminate\Support\Facades\Route;

// Module registry. Each module owns its routes; no individual POS routes here.
// Extend this list as controllers for other modules are implemented.
$modules = [
    'inventory',
    'customers',
    'suppliers',
    'cashbox',
];

foreach ($modules as $module) {
    Route::name($module . '.')->group(
        __DIR__ . '/pos/' . $module . '.php'
    );
}
