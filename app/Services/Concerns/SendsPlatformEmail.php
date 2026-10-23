<?php

/*
 * ---------------------------------------------------------------------------
 * Shared mail sending.
 *
 * Every outgoing message renders a blade view inside the shared layout.
 *
 * A failure is logged with the view name and the reason, because a message
 * that quietly fails is worse than one that fails loudly: a missing template
 * looks exactly like a mail server problem from the outside.
 *
 * Anything the customer is waiting on, a sign in code or a reset code, rethrows
 * so the controller can say what went wrong instead of leaving them staring at
 * a form that will never accept anything. Everything else, an order copy or a
 * team notification, stays quiet: a mail problem must not stop an order being
 * placed.
 * ---------------------------------------------------------------------------
 */

namespace App\Services\Concerns;

use App\Models\Core\Setting;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use RuntimeException;

trait SendsPlatformEmail
{
    /**
     * @param  array{audience: string, type: string, title: string, link?: ?string, customer_id?: ?int}|null  $notify
     *         Pass this to also log a Messages entry. Logged first, before the send is even
     *         attempted, so a mail server problem can never make the notification disappear
     *         along with it. audience is either 'admin' (every administrator sees it) or
     *         'customer' (only customer_id sees it, in their own inbox).
     */
    protected function dispatchMail(string $to, string $subject, string $view, array $data = [], bool $critical = false, ?array $notify = null): bool
    {
        /* Recorded up front and unconditionally. Whatever happens to the
           email below, the action itself still gets remembered. */
        if ($notify) {
            $this->logNotification($notify);
        }

        if (! $to) {
            if ($critical) {
                throw new RuntimeException('No email address to send to.');
            }

            return false;
        }

        /* Caught here rather than inside the mailer, so the message names the
           template instead of reporting a generic view error */
        if (! View::exists($view)) {
            $message = 'Email template ' . $view . ' is missing.';
            Log::error($message);

            if ($critical) {
                throw new RuntimeException($message);
            }

            return false;
        }

        $data['subject'] = $subject;
        $data['company'] = Setting::get('company_name', config('company.legal_name'));
        $data['logo']    = Setting::image('logo', 'images/logo.png');
        $data['phone']   = Setting::get('contact_phone', config('company.phone'));
        $data['email']   = Setting::get('contact_email', config('company.email'));

        try {
            Mail::send($view, $data, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
            });

            return true;
        } catch (\Throwable $e) {
            Log::error('Platform email failed', [
                'to'      => $to,
                'view'    => $view,
                'subject' => $subject,
                'error'   => $e->getMessage(),
            ]);

            if ($critical) {
                throw new RuntimeException($e->getMessage(), 0, $e);
            }

            return false;
        }
    }

    private function logNotification(array $notify): void
    {
        try {
            $service = app(NotificationService::class);

            if (($notify['audience'] ?? 'admin') === 'customer') {
                $service->customer(
                    (int) ($notify['customer_id'] ?? 0),
                    $notify['type'],
                    $notify['title'],
                    $notify['message'] ?? null,
                    $notify['link'] ?? null
                );
            } else {
                $service->admin(
                    $notify['type'],
                    $notify['title'],
                    $notify['message'] ?? null,
                    $notify['link'] ?? null
                );
            }
        } catch (\Throwable $e) {
            /* A notification row failing to write must never take an email
               down with it */
            Log::error('Notification logging failed', ['error' => $e->getMessage()]);
        }
    }

    protected function adminAddress(): string
    {
        return Setting::get('contact_email', config('company.email'));
    }
}
