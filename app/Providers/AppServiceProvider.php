<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
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
            $admin = DB::table('admin')
                ->leftJoin('pelayanan', 'admin.id_admin', '=', 'pelayanan.id_admin')
                ->where('admin.id_admin', auth()->id())
                ->select('admin.*', 'pelayanan.*')
                ->first();

            $admins = DB::table('admin')
                ->where('id_admin', auth()->id())
                ->first();

            $profile = Profile::find(1);

            $view->with([
                'admin' => $admin,
                'admins' => $admins,
                'profile' => $profile,
            ]);
        });
    }
}
