<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\MappingRekomendasiNotifikasi;
use Auth;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) 
        {
            if (Auth::check()){
                $query=MappingRekomendasiNotifikasi::where('user_id', Auth::user()->id)
                            ->with('dlhp')
                            ->with('dtemuan')
                            ->with('drekom')
                            ->orderBy('created_at','desc')
                            ->get();
                \View::share('notificationData', $query);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
