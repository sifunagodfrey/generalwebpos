<?php

/*
|--------------------------------------------------------------------------
| Company Configuration
|--------------------------------------------------------------------------
|
| Everything here is a bootstrapping fallback only. Once su_settings is
| seeded, the admin panel reads branding, name, logo, and contact details
| from there instead, so a different company can reuse this codebase
| without touching this file. This file only covers the values needed
| before that table exists, or as a hard fallback if a setting is empty.
|
*/

return [

    'legal_name' => env('COMPANY_LEGAL_NAME', 'Suweka POS'),
    'phone'      => env('COMPANY_PHONE'),
    'email'      => env('COMPANY_EMAIL'),
    'address'    => env('COMPANY_ADDRESS', 'Kenya'),

];
