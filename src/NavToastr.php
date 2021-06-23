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
        $noti = '<script type="text/javascript">
                    new Toast({ message: 'hi', type: 'success' });
                </script>';

        return $noti;
    }

    public function error(string $message): self
    {
        return $this->addNotification(self::ERROR, $message);
    }

    public function info(string $message): self
    {
        return $this->addNotification(self::INFO, $message);
    }

    public function success(string $message): self
    {
        return $this->addNotification(self::SUCCESS, $message);
    }


    public function warning(string $message): self
    {
        return $this->addNotification(self::WARNING, $message);
    }


    public function addNotification(string $type, string $message): self
    {
        $this->notifications[] = [
            'type'    => in_array($type, $this->allowedTypes, true) ? $type : self::WARNING,
            'message' => $this->escapeSingleQuote($message),
            'options' => json_encode($options),
        ];

        $this->session->flash(self::NAVTOASTR_NOTI, $this->notifications);

        return $this;
    }


    public function to()
    {

    }


    public function named()
    {

    }


    public function push()
    {

    }


    private function escapeSingleQuote(string $value): string
    {
        return str_replace("'", "\\'", $value);
    }

}