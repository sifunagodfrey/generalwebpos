<?php

namespace App\Services\Licensing;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class PosLicenseService
{
    private const PUBLIC_KEY = 'lUXLROEx9Lcne0C0UTM5tdDah1trpY17WI+yTKhVDh4=';
    private const APP_CODE = 'general_shop';

    public function deviceFingerprint(): string
    {
        $file = storage_path('app/pos-license-device-id');
        if (!is_dir(dirname($file)) && !mkdir(dirname($file), 0700, true) && !is_dir(dirname($file))) {
            throw new \RuntimeException('License storage is unavailable.');
        }
        $handle = fopen($file, 'c+');
        if ($handle === false) {
            throw new \RuntimeException('License storage is unavailable.');
        }
        try {
            if (!flock($handle, LOCK_EX)) {
                throw new \RuntimeException('License storage is unavailable.');
            }
            rewind($handle);
            $stored = trim((string) stream_get_contents($handle));
            if ($stored !== '') {
                return $stored;
            }
            $fingerprint = hash('sha256', 'web-deployment|'.bin2hex(random_bytes(32)));
            rewind($handle);
            ftruncate($handle, 0);
            fwrite($handle, $fingerprint);
            fflush($handle);
            @chmod($file, 0600);
            return $fingerprint;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }

    public function isActive(): bool
    {
        if (!Schema::hasTable('su_license_state')) {
            return false;
        }
        $row = DB::table('su_license_state')->orderBy('id')->first();
        if (!$row || $row->status !== 'active') {
            return false;
        }
        return $this->leaseCheck((string) $row->lease_payload, (string) $row->lease_signature, $this->deviceFingerprint()) === 'ok';
    }

    /** Returns a diagnostic code; never logs or exposes key material or lease contents. */
    private function leaseCheck(string $payload64, string $signature64, string $expectedFingerprint): string
    {
        if (!function_exists('sodium_crypto_sign_verify_detached')) {
            return 'sodium_missing';
        }
        $payload = base64_decode($payload64, true);
        $signature = base64_decode($signature64, true);
        $publicKey = base64_decode(self::PUBLIC_KEY, true);
        if ($payload === false || $signature === false || $publicKey === false || strlen($signature) !== 64 || strlen($publicKey) !== 32) {
            return 'malformed_signed_lease';
        }
        if (!sodium_crypto_sign_verify_detached($signature, $payload, $publicKey)) {
            return 'signature_mismatch';
        }
        $claims = json_decode($payload, true);
        if (!is_array($claims)) {
            return 'invalid_claims';
        }
        if (($claims['pos_app_code'] ?? null) !== self::APP_CODE) {
            return 'app_mismatch';
        }
        if (($claims['device_fingerprint'] ?? null) !== $expectedFingerprint) {
            return 'device_mismatch';
        }
        if (($claims['status'] ?? null) !== 'active') {
            return 'inactive_lease';
        }
        if (!empty($claims['expires_at'])) {
            try {
                if (\Carbon\CarbonImmutable::parse((string) $claims['expires_at'])->isPast()) {
                    return 'expired_lease';
                }
            } catch (\Throwable $e) {
                return 'invalid_expiry';
            }
        }
        return 'ok';
    }

    public function activate(array $input): void
    {
        $fingerprint = $this->deviceFingerprint();
        try {
            $response = Http::acceptJson()->timeout(20)->post('https://suweka.com/api/pos/v1/activate', [
                'license_key' => trim($input['license_key']),
                'pos_app_code' => self::APP_CODE,
                'device_fingerprint' => $fingerprint,
                'device_name' => config('app.name', 'Suweka POS').' Web',
                'registrant_name' => $input['name'],
                'registrant_email' => $input['email'],
            ]);
        } catch (\Throwable $e) {
            Log::warning('POS license activation connection failed', ['reason' => 'network_error']);
            throw ValidationException::withMessages(['license_key' => 'Unable to connect. Please try again.']);
        }

        $json = $response->json();
        if (!is_array($json) || ($json['ok'] ?? false) !== true) {
            $code = is_array($json) ? (string) ($json['error'] ?? 'unknown') : 'invalid_response';
            Log::warning('POS license activation rejected', ['reason' => preg_replace('/[^a-z0-9_\-]/i', '', substr($code, 0, 64)), 'http_status' => $response->status()]);
            $message = $code === 'seat_limit_reached'
                ? 'No available license slots. Please contact support.'
                : 'Unable to activate. Please check your key or contact support.';
            throw ValidationException::withMessages(['license_key' => $message]);
        }

        $lease = $json['lease'] ?? null;
        $reason = is_array($lease)
            ? $this->leaseCheck((string) ($lease['payload'] ?? ''), (string) ($lease['signature'] ?? ''), $fingerprint)
            : 'lease_missing';
        if ($reason !== 'ok') {
            Log::warning('POS license response verification failed', [
                'reason' => $reason,
                'http_status' => $response->status(),
                'lease_key_id_present' => is_array($lease) && !empty($lease['key_id']),
            ]);
            throw ValidationException::withMessages(['license_key' => 'Unable to activate. Please contact support.']);
        }

        // Never grant access unless the signed response has passed verification.
        DB::table('su_license_state')->updateOrInsert(['id' => 1], [
            'license_key' => trim($input['license_key']),
            'pos_app_code' => self::APP_CODE,
            'device_fingerprint' => $fingerprint,
            'registrant_name' => $input['name'],
            'status' => 'active',
            'lease_payload' => $lease['payload'],
            'lease_signature' => $lease['signature'],
            'lease_key_id' => $lease['key_id'] ?? null,
            'last_verified_at' => now(),
            'restricted_reason' => null,
            'support_url' => null,
            'updated_at' => now(),
        ]);
    }
}
