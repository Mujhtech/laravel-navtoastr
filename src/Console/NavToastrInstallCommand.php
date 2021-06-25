<?php

/*
 * This file is part of the Laravel Navigation Toastr package.
 *
 * (c) Mujtech Mujeeb <mujeeb.muhideen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mujhtech\NavToastr\Console;

use Illuminate\Console\Command;
use Mujhtech\NavToastr\CommandHelper;

class NavToastrInstallCommand extends Command {

    protected $signature = 'nav-toastr:install '.
        '{--force : Overwrite existing views by default}'.
        '{--type= : Installation type, Available type: none, enhanced & full.}'.
        '{--only= : Install only specific part, Available parts: assets & config. This option can not used with the with option.}'.
        '{--interactive : The installation will guide you through the process}';

    protected $description = 'Install all the required files for NavToastr';


    protected $package_path = __DIR__.'/../../';

    protected $assets_path = 'vendor/nav-toastr/assets/';

    protected $assets_package_path = 'public/nav-toastr/';



    protected $assets = [
        'css' => [
            'name' => 'Css Folder',
            'package_path' => 'css',
            'assets_path' => 'css',
        ],
        'js' => [
            'name' => 'JS Folder',
            'package_path' => 'js',
            'assets_path' => 'js',
        ],
    ];

    

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->option('only')) {
            switch ($this->option('only')) {
            case 'config':
                $this->exportConfig();
                break;
            case 'assets':
                $this->exportAssets();
                break;
            default:
                $this->error('Invalid option!');
                break;
            }

            return;
        }

        if ($this->option('type') == 'basic' || $this->option('type') != 'none' || ! $this->option('type')) {
            $this->exportConfig();
            $this->exportAssets();
        }

        $this->info('Laravel NavToastr Installation complete.');
    }


    /**
     * Install the config file.
     */
    protected function exportConfig()
    {
        if ($this->option('interactive')) {
            if (! $this->confirm('Install the package config file?')) {
                return;
            }
        }
        if (file_exists(config_path('nav-toastr.php')) && ! $this->option('force')) {
            if (! $this->confirm('The Laravel NavToastr configuration file already exists. Do you want to replace it?')) {
                return;
            }
        }
        copy(
            $this->packagePath('config/nav-toastr.php'),
            config_path('nav-toastr.php')
        );

        $this->comment('Configuration Files installed successfully.');
    }


    /**
     * Copy all the content of the Assets Folder to Public Directory.
     */
    protected function exportAssets()
    {
        if ($this->option('interactive')) {
            if (! $this->confirm('Install the basic package assets?')) {
                return;
            }
        }

        foreach ($this->assets as $asset_key => $asset) {
            $this->copyAssets($asset_key, $this->option('force'));
        }

        $this->comment('Basic Assets Installation complete.');
    }



    protected function copyAssets($asset_name, $force = false)
    {
        if (! isset($this->assets[$asset_name])) {
            return;
        }

        $asset = $this->assets[$asset_name];

        if (is_array($asset['package_path'])) {
            foreach ($asset['package_path'] as $key => $asset_package_path) {
                $asset_assets_path = $asset['assets_path'][$key];
                CommandHelper::copyDirectory(
                    $this->packagePath($this->assets_package_path).$asset_package_path,
                    public_path($this->assets_path).$asset_assets_path,
                    $force,
                    ($asset['recursive'] ?? true),
                    ($asset['ignore'] ?? [])
                );
            }
        } else {
            CommandHelper::copyDirectory(
                $this->packagePath($this->assets_package_path).$asset['package_path'],
                public_path($this->assets_path).$asset['assets_path'],
                $force,
                ($asset['recursive'] ?? true),
                ($asset['ignore'] ?? [])
            );
        }

    }

    /**
     * Get Package Path.
     */
    protected function packagePath($path)
    {
        return $this->package_path.$path;
    }

    /**
     * Get Protected.
     *
     * @return array
     */
    public function getProtected($var)
    {
        return $this->{$var};
    }


}