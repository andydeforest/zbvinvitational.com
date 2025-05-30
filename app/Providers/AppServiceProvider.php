<?php

namespace App\Providers;

use App\Facades\Settings as SettingsFacade;
use App\Services\StripeGateway;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Stripe\StripeClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(StripeGateway::class, function ($app) {
            return new StripeGateway(
                new StripeClient(config('services.stripe.secret'))
            );
        });

        AliasLoader::getInstance()->alias('Settings', SettingsFacade::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // user data (in the admin UI)
        Inertia::share('auth.user', fn () => auth()->user() ? [
            'id' => auth()->id(),
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ] : null);
    }
}
