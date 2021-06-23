<?php

/*
 * This file is part of the Laravel Navigation Toastr package.
 *
 * (c) Mujtech Mujeeb <mujeeb.muhideen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mujhtech\NavToastr\Facades;

use Illuminate\Support\Facades\Facade;

class NavToastr extends Facade
{
    /**
     * Get the registered name of the component
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'nav-toastr';
    }
}