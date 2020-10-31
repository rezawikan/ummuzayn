<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Pattern\Cart\Cart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->app->singleton(Cart::class, function ($app) {
        //     if ($app->auth->guard('customers')->user()) {
        //         return new Cart($app->auth->user());
        //     }
        // });
    }
}
