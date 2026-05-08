<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Request as BarangayRequest;
use App\Models\Concern;
use App\Models\Blotter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        View::composer('layouts.navigation', function ($view) {

            $view->with([
                'certCount' => BarangayRequest::where('admin_read', false)->count(),
                'concernCount' => Concern::where('admin_read', false)->count(),
                'blotterCount' => Blotter::where('admin_read', false)->count(),
            ]);
        });
    }
}
