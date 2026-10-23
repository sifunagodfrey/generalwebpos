<?php

/*
|--------------------------------------------------------------------------
| View Configuration
|--------------------------------------------------------------------------
|
| Laravel 12 ships without this file and falls back to resources/views only,
| which is why a template under platform/ is reported as not found. Both
| directories are registered below.
|
| platform/ is listed first so a template there wins over one of the same name
| in resources/views. Keep resources/views in the list as well: the Blade
| components and the error pages live there.
|
*/

return [

    'paths' => [
        base_path('platform'),
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | Where the compiled Blade templates are written. Run view:clear after
    | changing the paths above so stale compiled files are not reused.
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),

];
