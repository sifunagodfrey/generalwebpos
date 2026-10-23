<?php

/*
 * ---------------------------------------------------------------------------
 * Application bootstrapping.
 *
 * Shares the values every admin page needs, so no controller has to remember
 * to fetch them: system settings and the alert counts shown in the sidebar.
 * ---------------------------------------------------------------------------
 */

namespace App\Providers;

use App\Models\Core\Setting;
use App\Models\System\Notification;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Services\UploadService::class);
        $this->app->singleton(\App\Services\NotificationService::class);
    }

    public function boot(): void
    {
        Paginator::useTailwind();
        $this->configureTurboAssetTracking();

        /* -----------------------------------------------------------------
           Nothing below can run before the tables exist, which matters when
           artisan is used on a database that has not been imported yet.
        ----------------------------------------------------------------- */
        if (!$this->tablesReady()) {
            return;
        }

        $this->shareAdminData();
        $this->registerDirectives();
    }

    private function tablesReady(): bool
    {
        try {
            return Schema::hasTable('su_settings');
        } catch (\Throwable $e) {
            return false;
        }
    }

    /* -----------------------------------------------------------------
       Turbo Drive only forces a real browser reload for a head asset
       tag when that tag is marked data-turbo-track="reload" and its
       content actually changed between two visits. Without this, a tab
       left open across a deploy would keep running the JS bundle it
       already loaded indefinitely, Turbo has no reason on its own to
       notice the new, differently hashed filename.
    ----------------------------------------------------------------- */
    private function configureTurboAssetTracking(): void
    {
        Vite::useScriptTagAttributes(['data-turbo-track' => 'reload']);
        Vite::useStyleTagAttributes(['data-turbo-track' => 'reload']);
    }

    /* ---------------------------------------------------------------------
       Admin panel
       Counts drive the badges beside each item in the sidebar.
    --------------------------------------------------------------------- */
    private function shareAdminData(): void
    {
        View::composer('manage.*', function ($view) {

            $user = auth()->user();

            if (!$user) {
                return;
            }

            $view->with([
                'siteSettings' => Setting::allCached(),
                'adminAlerts' => Notification::forUser($user->id)->unread()->latest()->limit(8)->get(),
                'adminAlertCount' => Notification::forUser($user->id)->unread()->count(),
            ]);
        });
    }

    /* ---------------------------------------------------------------------
       Blade directives

       @can_do renders a block only when the signed in user holds the given
       permission.
    --------------------------------------------------------------------- */
    private function registerDirectives(): void
    {
        Blade::if('can_do', function (string $permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });
    }
}