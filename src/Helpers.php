<?php


if (! function_exists("navtoastr"))
{
    function navtoastr() {

        return app()->make('nav-toastr');
    }
}