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
use Illuminate\Support\Facades\Config;
use Illuminate\Config\Repository;
use Illuminate\Session\SessionManager;

class NavToastr {


    const ERROR = 'error';
    const INFO = 'info';
    const SUCCESS = 'success';
    const WARNING = 'warning';


    const NAVTOASTR_NOTI = 'navtoastr::noti';


    protected $notifications = [];


    protected $session;


    protected $config;



    protected $allowedTypes = [self::ERROR, self::INFO, self::SUCCESS, self::WARNING];



    public function __construct(SessionManager $session, Repository $config)
    {
        $this->session = $session;

        $this->config = $config;

        $this->notifications = $this->session->get(self::NAVTOASTR_NOTI, []);
    }



    public function show(): string
    {
        $noti = '<script type="text/javascript">'.$this->notificationsAsString().'</script>';

        $this->session->forget(self::NAVTOASTR_NOTI);

        return $noti;
    }


    public function notificationsAsString(): string
    {
        return implode('', array_slice($this->notifications(), -2));
    }


    public function notifications(): array
    {
        return array_map(
            function ($n) {
                return $this->toast($n['type'], $n['message'], $n['enableCustomButton']);
            },
            $this->session->get(self::NAVTOASTR_NOTI, [])
        );
    }


    public function toast(string $type, string $message = '', bool $enableCustomButton = false): string
    {
        $custombutton = $enableCustomButton ? $this->options() : '';

        return "new Toast({ message: '$message', type: '$type', $custombutton });";
    }


    public function error(string $message, bool $enableCustomButton = false): self
    {
        return $this->addNotification(self::ERROR, $message, $enableCustomButton);
    }

    public function info(string $message, bool $enableCustomButton = false): self
    {
        return $this->addNotification(self::INFO, $message, $enableCustomButton);
    }

    public function success(string $message, bool $enableCustomButton = false): self
    {
        return $this->addNotification(self::SUCCESS, $message, $enableCustomButton);
    }


    public function warning(string $message, bool $enableCustomButton = false): self
    {
        return $this->addNotification(self::WARNING, $message, $enableCustomButton);
    }


    public function addNotification(string $type, string $message, bool $enableCustomButton = false): self
    {
        $this->notifications[] = [
            'type'    => in_array($type, $this->allowedTypes, true) ? $type : self::WARNING,
            'message' => $this->escapeSingleQuote($message),
            'enableCustomButton' => $enableCustomButton,
        ];

        $this->session->flash(self::NAVTOASTR_NOTI, $this->notifications);

        return $this;
    }


    public function options(): string
    {

        $option = array_map(
            function ($n) {
                if(array_key_exists('reload', $n) && $n['reload'] == true)
                    return array(
                        'text : '.$n['text'].',onClick : function(){window.location.reload();}'
                    );
                return array(
                    'text : '.$n['text'].', onClick : function(){window.open("'.$n['url'].'");}'
                );
            },
            $this->config->get('nav-toastr.custombuttons', [])
        );


        return 'customButtons : '.json_encode($option).'';
    }


    public function back()
    {
        return back();
    }


    public function to( string $url )
    {
        return redirect($url);
    }


    public function named( string $name )
    {

        return redirect()->route($name);

    }


    public function out( string $url )
    {

        return redirect()->away($url);

    }


    private function escapeSingleQuote(string $value): string
    {
        return str_replace("'", "\\'", $value);
    }


    public function clear() {
        $this->notifications = [];
    }

}