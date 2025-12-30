<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DetectCurrency
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('currency')) {
            $country = null;

            // Quick debug override for local/testing:
            // ?country=IN or ?country=US
            if ($request->has('country')) {
                $country = strtoupper($request->query('country'));
            }

            // Cloudflare users get this header for free
            if (!$country && $request->hasHeader('CF-IPCountry')) {
                $country = strtoupper($request->header('CF-IPCountry'));
            }

            // Fallback API (free & fast). You can swap to any provider you like.
            if (!$country) {
                $ip = $this->clientIp($request);
                try {
                    $res = Http::timeout(2.5)->retry(2, 200)->get("https://ipwho.is/{$ip}?fields=country_code");
                    if ($res->ok()) {
                        $country = strtoupper((string) data_get($res->json(), 'country_code'));
                    }
                } catch (\Throwable $e) {
                    Log::warning('GeoIP failed: '.$e->getMessage());
                }
            }

            $country = $country ?: 'IN';

            $currency = match ($country) {
                'IN' => 'INR',
                'US' => 'USD',
                default => 'USD', // all others default to USD
            };

            session([
                'country_code' => $country,
                'currency' => $currency,
            ]);
        }

        return $next($request);
    }

    protected function clientIp(Request $request): string
    {
        $ip = $request->ip();
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return $request->query('ip', '1.1.1.1'); // fake IP for local dev
        }
        return $ip;
    }
}
