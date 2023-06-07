<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use App\View\Components\Link;
use App\View\Components\Style;
use App\View\Components\Script;
use App\View\Components\ConfirmDialog;
use App\View\Components\QrCodeReaderDialog;
use App\View\Components\VirticalMenu;
use App\View\Components\HorizontalTab;
use App\View\Components\Dialogs\UploadFile;
use App\View\Components\Dialogs\Reserve;

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
        //コンポーネント
        Blade::component('link', Link::class);
        Blade::component('style', Style::class);
        Blade::component('script', Script::class);

        Blade::component('confirm-dialog', ConfirmDialog::class);
        Blade::component('qr-code-reader-dialog', QrCodeReaderDialog::class);
        Blade::component('virtical-menu', VirticalMenu::class);
        Blade::component('horizontal-tab', HorizontalTab::class);

        Blade::component('dialogs.upload-file', UploadFile::class);
        Blade::component('dialogs.reserve', Reserve::class);

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

        //コンポーザー
        View::composer(['pages.*'], 'App\View\Composers\UserComposer');
    }
}
