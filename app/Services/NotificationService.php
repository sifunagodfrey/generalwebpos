<?php

namespace App\Services;

use App\Models\System\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class NotificationService
{
    /**
     * @param  int|null  $forUserId  Who should see this. Leave null to broadcast to every admin.
     */
    public function log(
        string $type,
        string $title,
        ?string $message = null,
        ?string $link = null,
        ?int $forUserId = null,
        ?User $causer = null,
        string $audience = 'admin'
    ): Notification {
        $causer = $causer ?? (Auth::check() ? Auth::user() : null);

        return Notification::create([
            'user_id'     => $forUserId,
            'audience'    => $audience,
            'causer_id'   => $causer?->id,
            'causer_name' => $causer ? trim($causer->first_name . ' ' . $causer->last_name) : null,
            'title'       => $title,
            'message'     => $message,
            'type'        => $type,
            'link'        => $link,
            'ip_address'  => Request::ip(),
        ]);
    }

    public function admin(string $type, string $title, ?string $message = null, ?string $link = null, ?User $causer = null): Notification
    {
        return $this->log($type, $title, $message, $link, null, $causer, 'admin');
    }

    public function customer(int $customerId, string $type, string $title, ?string $message = null, ?string $link = null, ?User $causer = null): Notification
    {
        return $this->log($type, $title, $message, $link, $customerId, $causer, 'customer');
    }
}
