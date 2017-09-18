<?php

namespace App\Providers;

use App\Channel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        \View::composer('threads.create', function($view) {
        \View::composer('*', function($view) {
            $view->with('channels', \App\Channel::all());
        });

        // this one triggers before the databasemigrations of the tests
//        \View::share('channels', Channel::all()); // if too much, make another provider
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
