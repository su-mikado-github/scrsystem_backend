<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

use App\View\Components\Link;
use App\View\Components\Style;
use App\View\Components\Script;
use App\View\Components\ConfirmDialog;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Blade::component('link', Link::class);
        Blade::component('style', Style::class);
        Blade::component('script', Script::class);

        Blade::component('confirm-dialog', ConfirmDialog::class);
        Blade::component('qr-code-reader-dialog', QrCodeReaderDialog::class);

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
            return "<?php logger()->debug({$expression}) ?>";
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
