<?php

/*
 * ---------------------------------------------------------------------------
 * Messages sent to admins and staff: sign in codes, password resets, and
 * account lifecycle notices.
 * ---------------------------------------------------------------------------
 */

namespace App\Services;

use App\Models\User;
use App\Services\Concerns\SendsPlatformEmail;

class AdminEmailService
{
    use SendsPlatformEmail;

    public function sendLoginOtp(User $user, string $code): bool
    {
        return $this->dispatchMail($user->email, 'Admin sign in code', 'emails.system.otp', [
            'name' => $user->first_name,
            'code' => $code,
        ], true);
    }

    /* The reset flow calls this with an integer code, so the type is loose
       on purpose rather than forcing a cast at every call site */
    public function sendPasswordResetOtp(object $user, int|string $code): bool
    {
        return $this->dispatchMail($user->email, 'Reset your admin password', 'emails.system.password-reset', [
            'name' => $user->first_name ?? $user->name ?? 'there',
            'code' => (string) $code,
        ], true);
    }

    /* Sent once a staff account is created, carrying the first password */
    public function sendUserCreatedEmail(object $user, string $plainPassword): bool
    {
        return $this->dispatchMail($user->email, 'Your admin account', 'emails.system.user-created', [
            'name'     => $user->first_name ?? $user->name ?? 'there',
            'email'    => $user->email,
            'password' => $plainPassword,
            'loginUrl' => route('manage.auth.login'),
        ], false, [
            'audience' => 'admin',
            'type'     => 'account.created',
            'title'    => 'Staff account created: ' . ($user->first_name ?? $user->name ?? $user->email),
            'link'     => route('manage.system-users.show', $user->id),
        ]);
    }

    public function sendUserUpdatedEmail(object $user, array $changes = []): bool
    {
        return $this->dispatchMail($user->email, 'Your admin account was updated', 'emails.system.user-updated', [
            'name'    => $user->first_name ?? $user->name ?? 'there',
            'changes' => $changes,
        ], false, [
            'audience' => 'admin',
            'type'     => 'account.updated',
            'title'    => 'Staff account updated: ' . ($user->first_name ?? $user->name ?? $user->email),
            'link'     => route('manage.system-users.show', $user->id),
        ]);
    }

    public function sendUserDeletedEmail(object $user): bool
    {
        return $this->dispatchMail($user->email, 'Your admin account was removed', 'emails.system.user-deleted', [
            'name' => $user->first_name ?? $user->name ?? 'there',
        ], false, [
            'audience' => 'admin',
            'type'     => 'account.deleted',
            'title'    => 'Staff account removed: ' . ($user->first_name ?? $user->name ?? $user->email),
        ]);
    }
}
