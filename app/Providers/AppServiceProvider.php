<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once(app_path('Enum.php'));
        require_once(app_path('LineApi.php'));
        require_once(app_path('Helper/HelperFunctions.php'));

        $this->app->singleton(\App\LineApi::class, function($app) {
            return new \App\LineApi();
        });

        if (config('app.env') !== 'production') {
            DB::listen(function ($query) {
                logger()->info(sprintf("time=>%ds sql=>%s bindings=>%s", $query->time, $query->sql, var_export($query->bindings, true)));
            });
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Validator::extend('hiragana', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[ぁ-ゞ]+$/u', $value);
        });
        Validator::extend('month', function ($attribute, $value, $parameters, $validator) {
            return (1 <= $value && $value <= 12);
        });
        Validator::extend('day', function ($attribute, $value, $parameters, $validator) {
            return (1 <= $value && $value <= 31);
        });
    }
}
