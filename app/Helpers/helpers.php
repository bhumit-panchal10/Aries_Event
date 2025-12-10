<?php

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use App\Models\EmailTemplate;
use App\Models\AppMaster;
use App\Models\Reference;
use App\Models\RatingDetail;
use Illuminate\Support\Facades\Mail;


function FolderPath($folderName)
{
    if ($_SERVER['SERVER_NAME'] == "127.0.0.1") {
        return $_SERVER['DOCUMENT_ROOT'] . '/' . $folderName;
    } else {
        return $_SERVER['DOCUMENT_ROOT'] . '/oro_veda/' . $folderName;
    }
}

if (! function_exists('current_currency')) {
    function current_currency(): string
    {
        return session('currency', 'USD');
    }
}

if (! function_exists('money')) {
    function money(null|float|int|string $amount): string
    {
        if ($amount === null || $amount === '') return '-';
        $cur = current_currency();
        $symbol = $cur === 'INR' ? '₹' : '$';
        return $symbol . number_format((float)$amount, 2);
    }
}

/** Price (rate or usd_rate) */
if (! function_exists('product_price')) {
    function product_price($product): ?float
    {
        $cur = current_currency();

        $get = function ($obj, string $key) {
            return is_array($obj) ? ($obj[$key] ?? null) : ($obj->{$key} ?? null);
        };

        $v = $cur === 'INR'
            ? ($get($product, 'rate'))
            : ($get($product, 'usd_rate'));

        return ($v === null || $v === '') ? null : (float)$v;
    }
}

/** Cut/compare-at price (supports cut_rate/cut_price and usd_cut_rate/usd_cut_price) */
if (! function_exists('product_cut_price')) {
    function product_cut_price($product): ?float
    {
        $cur = current_currency();

        $get = function ($obj, string $key) {
            return is_array($obj) ? ($obj[$key] ?? null) : ($obj->{$key} ?? null);
        };

        if ($cur === 'INR') {
            $v = $get($product, 'cut_rate');
            if ($v === null || $v === '') $v = $get($product, 'cut_price');
        } else {
            $v = $get($product, 'usd_cut_rate');
            if ($v === null || $v === '') $v = $get($product, 'usd_cut_price');
        }

        return ($v === null || $v === '') ? null : (float)$v;
    }
}

if (!function_exists('getCountryCode')) {
    function getCountryCode($ip)
    {
        // $ip = '8.8.8.8';
        $response = @file_get_contents("http://ip-api.com/json/{$ip}");

        if ($response) {
            $data = json_decode($response, true);
            if (!empty($data['countryCode'])) {
                return $data['countryCode']; // e.g. 'IN'
            }
        }
        return 'US';
    }
}
