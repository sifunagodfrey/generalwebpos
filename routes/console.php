<?php

/* -------------------
   Console entry point.

   Scheduled tasks live here rather than a Kernel class, matching this
   Laravel version's default layout. Requires the server's own cron to call
   `php artisan schedule:run` every minute, nothing runs from this file on
   its own.
------------------- */

use Illuminate\Support\Facades\Schedule;

Schedule::command('recordings:prune')->daily();
