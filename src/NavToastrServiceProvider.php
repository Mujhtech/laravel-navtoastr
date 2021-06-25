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
use Illuminate\Container\Container;
use Mujhtech\NavToastr\Console\NavToastrInstallCommand;


include_once(__DIR__.'/Helpers.php');


class NavToastrServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('nav-toastr', function (Container $app) {

            return new NavToastr($app['session'], $app['config']);

        });

        $this->registerBladeDirectives();
    }


    /**
     * Bootstrap the application events.
     *
     * @return void
     */


    public function boot()
    {

        $this->registerCommands();

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


    public function registerBladeDirectives()
    {
        Blade::directive('navtoastrRender', function () {
            return "<?php echo app('nav-toastr')->show(); ?>";
        });

        Blade::directive('navtoastrCss', function () {
            return "<?php echo toastr_css(); ?>";
        });

        Blade::directive('navtoastrJs', function () {
            return "<?php echo toastr_js(); ?>";
        });

    }

}