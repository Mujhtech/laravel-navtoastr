<?php


/*
 * This file is part of the Laravel Navigation Toastr package.
 *
 * (c) Mujtech Mujeeb <mujeeb.muhideen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mujhtech\NavToastr;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Mujhtech\Varspay\Console\NavToastrInstallCommand;


class VarspayServiceProvider extends ServiceProvider
{


     /*
    * Indicates if loading of the provider is deferred.
    *
    * @var bool
    */


    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('nav-toastr', function ($app) {

            return new NavToastr;

        });

        $this->registerBladeDirectives();
    }


    public function boot()
    {
        $config = realpath(__DIR__.'/../config/nav-toastr.php');

        $this->publishes([
            $config => config_path('nav-toastr.php')
        ]);
        $this->registerCommands();
        $this->loadViews();
        $this->loadConfig();
        $this->loadPublic();
    }


    /**
    * Get the services provided by the provider
    * @return array
    */

    public function provides()
    {
        return ['nav-toastr'];
    }


    private function registerCommands()
    {
        $this->commands([
            NavToastrInstallCommand::class,
        ]);
    }

    private function loadViews()
    {
        $viewsPath = $this->packagePath('resources/views');
        $this->loadViewsFrom($viewsPath, 'navtoastr');
    }

    private function loadConfig()
    {
        $configPath = $this->packagePath('config/laravel-mentor.php');
        $this->mergeConfigFrom($configPath, 'navtoastr');
    }

    private function loadPublic()
    {
        $publicPath = $this->packagePath('public/nav-toastr');
        $this->publishes([
            $publicPath => public_path('navtoastr'),
        ], 'public');
    }


    public function registerBladeDirectives()
    {
        Blade::directive('navtoastr_render', function () {
            return "<?php echo app('toastr')->show(); ?>";
        });

        Blade::directive('navtoastr_css', function () {
            return "<?php echo toastr_css(); ?>";
        });

        Blade::directive('navtoastr_js', function () {
            return "<?php echo toastr_js(); ?>";
        });

    }

}