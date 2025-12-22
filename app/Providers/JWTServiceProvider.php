<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class JWTServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if (!class_exists('Lcobucci\JWT\Signer\Hmac\Sha256')) {
            // Try to find the correct class
            if (class_exists('Lcobucci\JWT\Signer\Hmac')) {
                // The class might be in a different location
                // Create an alias or fix the reference
                class_alias(\Lcobucci\JWT\Signer\Hmac::class, 'Lcobucci\JWT\Signer\Hmac\Sha256');
            }
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
