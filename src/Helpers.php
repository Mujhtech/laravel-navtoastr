<?php


if (! function_exists("navtoastr"))
{
    function navtoastr(string $message = null, string $type = 'success', bool $enableCustomButton = false) {

        if (is_null($message)) {

            return app('nav-toastr');
            
        }

        return app('nav-toastr')->addNotification($type, $message, $enableCustomButton);
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