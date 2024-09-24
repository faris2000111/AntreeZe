<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Support\Facades\View;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $admin = Admin::where('id_admin', auth()->id())->first();
            $view->with('admin', $admin);
        });
        View::composer('*', function ($view) {
            $profile = Profile::find(1); // Find the profile you want to share
            $view->with('profile', $profile);
        });
    }
}
