<?php


if (! function_exists("navtoastr"))
{
    function navtoastr() {

        return app()->make('nav-toastr');
    }
}


if(! function_exists("toastr_css") ){

    function toastr_css(){

        return '<link rel="stylesheet" type="text/css" href="'.asset('vendor/nav-toastr/assets/css/app.css').'">';

    }

}


if(! function_exists("toastr_js") ){

    function toastr_js(){

        return '<script type="text/javascript" src="'.asset('vendor/nav-toastr/assets/js/app.js').'"></script>';

    }
    
}