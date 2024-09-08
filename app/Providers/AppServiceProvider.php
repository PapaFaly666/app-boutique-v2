<?php

namespace App\Providers;

use App\Repositories\ClientRepositoryImpl;
use App\Services\ClientServiceImpl;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('client_repository', function($app){
            return new ClientRepositoryImpl();
        });

        $this->app->singleton('clientservice',function($app){
            return  new ClientServiceImpl();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 
    }
}
