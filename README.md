<h1 align="center">Toastr.js notifications for Laravel 5 and Lumen</h1>

> I'm working on a more advanced and more flexible solution for Laravel and Symfony, that include more drivers like : Tailwindcss, bootstrap, Noty, Sweet ALert, Notyf and Pnotify

> I'm currently documenting all parts, but if you have time go check it here : https://php-flasher.github.io/
> <p align="center"><img width="600" alt="flasher" src="https://user-images.githubusercontent.com/10859693/102468596-03317180-4052-11eb-9df3-44dc6235b238.png"></p>


<p align="center">:eyes: This package helps you to add <a href="https://github.com/CodeSeven/toastr">toastr.js</a> notifications to your Laravel 5 and Lumen projects.</p>

<p align="center">
    <a href="https://packagist.org/packages/yoeunes/toastr"><img src="https://poser.pugx.org/yoeunes/toastr/v/stable" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/yoeunes/toastr"><img src="https://poser.pugx.org/yoeunes/toastr/v/unstable" alt="Latest Unstable Version"></a>
    <a href="https://scrutinizer-ci.com/g/yoeunes/toastr/build-status/master"><img src="https://scrutinizer-ci.com/g/yoeunes/toastr/badges/build.png?b=master" alt="Build Status"></a>
    <a href="https://scrutinizer-ci.com/g/yoeunes/toastr/?branch=master"><img src="https://scrutinizer-ci.com/g/yoeunes/toastr/badges/quality-score.png?b=master" alt="Scrutinizer Code Quality"></a>
    <a href="https://scrutinizer-ci.com/g/yoeunes/toastr/?branch=master"><img src="https://scrutinizer-ci.com/g/yoeunes/toastr/badges/coverage.png?b=master" alt="Code Coverage"></a>
    <a href="https://packagist.org/packages/yoeunes/toastr"><img src="https://poser.pugx.org/yoeunes/toastr/downloads" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/yoeunes/toastr"><img src="https://poser.pugx.org/yoeunes/toastr/license" alt="License"></a>
</p>

<p align="center"><img width="300" alt="toastr" src="https://user-images.githubusercontent.com/10859693/39634578-1a9f121a-4fb3-11e8-8863-d64fad42901b.png"></p>

## Install

You can install the package using composer

```sh
$ composer require mujhtech/nav-toastr
```

Then add the service provider to `config/app.php`. In Laravel versions 5.5 and beyond, this step can be skipped if package auto-discovery is enabled.

```php
'providers' => [
    ...
    Mujhtech\NavToastr\NavToastrServiceProvider::class
    ...
];
```

To install the configuration and assets file run:
 
```sh
$ php artisan navtoastr:install"
```

## Usage:

Include [app.css] and [app.js](https://github.com/ireade/Toast.js) in your view template: 

1. Link to app.css `<link href="src/Toast.css" rel="stylesheet"/>` or `@navtoastrCss`
2. Link to app.js `<script src="src/Toast.js"></script>` or `@navtoastrJs` 

4. use `navtoastr()` helper function inside your controller to set a toast notification for info, success, warning or error
```php
// Display an info toast with no title
navtoastr()->info('Are you the 6 fingered man?')
```

as an example:
```php
<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\PostRequest;
use Illuminate\Database\Eloquent\Model;

class UserController extends Controller
{
    public function store(UserRequest $request)
    {
        $post = User::create($request->only(['username', 'password']));

        if ($post instanceof Model) {

            navtoastr()->success('Data has been saved successfully!');

            return navtoastr()->named('posts.index');
        }

        navtoastr()->error('An error has occurred please try again later.');

        return navtoastr()->back();
    }
}
```

After that add the `@navtoastrRender` at the bottom of your view to actualy render the nav-toastr notifications.

```blade
<!doctype html>
<html>
    <head>
        <title>Nav Toastr.js</title>
        @navtoastrCss
    </head>
    <body>
        
    </body>
    @navtoastrJs
    @navtoastrRender
</html>
```
### Other Options

```php
// Set a info toast
navtoastr()->info('My name is Muhideen Mujeeb')

// Set a success toast
navtoastr()->success('Have fun storming the castle!')

// Set an error toast
navtoastr()->error('I do not think that word means what you think it means.')

// Set an warning toast

navtoastr()->warning('We do have the Kapua suite available.')
```
### Other api methods:
// You can also chain multiple messages together using method chaining
```php
navtoastr()->info('Are you the 6 fingered man?')->success('Have fun storming the castle!')->warning('doritos');
```

// you could replace `@navtoastrRender` by :
```php 
navtoastr()->render() or app('nav-toastr')->render()
```

// you can use `navtoastr('')` instead of `navtoastr()->success()`
```php
function toastr(string $message = null, string $type = 'success', string $title = '', bool $enableCustomButton = false);
```

so

* `navtoastr($message)` is equivalent to `navtoastr()->success($message)`
* `navtoastr($message, 'info', true)` is equivalent to `navtoastr()->info($message, true)`
* `navtoastr($message, 'warning', true)` is equivalent to `navtoastr()->warning($message, true)`
* `navtoastr($message, 'error', false) ` is equivalent to `navtoastr()->error($message, false)`

### configuration:
```php
// config/nav-toastr.php
<?php

return [
    
    'custombuttons' => [
        [
            'text'      => 'Refresh the page',
            'reload'    => true
        ],
        [
            'text'      => 'My Website',
            'url'       => 'https://mujh.tech'
        ],
        [
            'text'      => 'Twitter',
            'url'       => 'https://twitter.com/mujhtech'
        ]
    ],
];
```

## Credits

- [Mujhtech](https://github.com/mujhtech)
- [Ire Aderinokun](https://github.com/ireade)

## License

MIT
