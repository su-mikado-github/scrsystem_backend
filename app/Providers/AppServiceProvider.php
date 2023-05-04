<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Blade;
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

        $this->app->singleton(\App\LineApi::class, function($app) {
            return new \App\LineApi();
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
        Validator::extend('hiragana', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[ぁ-ゞ]+$/u', $value);
        });
        Validator::extend('month', function ($attribute, $value, $parameters, $validator) {
            return (1 <= $value && $value <= 12);
        });
        Validator::extend('day', function ($attribute, $value, $parameters, $validator) {
            return (1 <= $value && $value <= 31);
        });

        //blade拡張
        Blade::directive('use', function($expression) {
            return "<?php use {$expression}; ?>";
        });
        Blade::directive('eval', function($expression) {
            return "<?php {$expression}; ?>";
        });
        Blade::directive('val', function ($expression) {
            return "<?=({$expression}) ?>";
        });
        Blade::directive('debug', function($expression) {
            return "<?php logger()->debug({$expression}); ?>";
        });
        Blade::directive('url', function($expression) {
            return "<?=url({$expression}) ?>";
        });
        Blade::directive('surl', function($expression) {
            return "<?=secure_url({$expression}) ?>";
        });
        Blade::directive('route', function($expression) {
            return "<?=route({$expression}) ?>";
        });
    }
}
