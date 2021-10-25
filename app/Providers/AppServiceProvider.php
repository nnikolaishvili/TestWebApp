<?php

namespace App\Providers;

use App\Services\Interfaces\FetchOrdersInterface;
use App\Services\Interfaces\FetchProductsInterface;
use App\Services\Storeden\StoredenOrdersService;
use App\Services\Storeden\StoredenProductsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FetchOrdersInterface::class, function () {
            return new StoredenOrdersService();
        });

        $this->app->bind(FetchProductsInterface::class, function () {
            return new StoredenProductsService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
