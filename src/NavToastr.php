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


    /**
     * Added notifications.
     *
     * @var array
     */
    protected $notifications = [];

    /**
     * Illuminate Session.
     *
     * @var \Illuminate\Session\SessionManager
     */
    protected $session;

    /**
     * Toastr config.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Allowed toast types.
     *
     * @var array
     */


    protected $allowedTypes = [self::ERROR, self::INFO, self::SUCCESS, self::WARNING];


    /**
     * Toastr constructor.
     *
     * @param SessionManager $session
     * @param Repository     $config
     */

    public function __construct(SessionManager $session, Repository $config)
    {
        $this->session = $session;

        $this->config = $config;

        $this->notifications = $this->session->get(self::NAVTOASTR_NOTI, []);

    }

    /**
     * Show the notifications' script tag.
     *
     * @return string
     */

    public function show(): string
    {
        $noti = '<script type="text/javascript">'.$this->notificationsAsString().'</script>';

        $this->session->forget(self::NAVTOASTR_NOTI);

        return $noti;
    }


    /**
     * @return string
     */

    public function notificationsAsString(): string
    {
        return implode('', array_slice($this->notifications(), -1));
    }



    /**
     * map over all notifications and create an array of toastrs.
     *
     * @return array
     */


    public function notifications(): array
    {
        return array_map(
            function ($n) {
                return $this->toast($n['type'], $n['message'], $n['enableCustomButton']);
            },
            $this->session->get(self::NAVTOASTR_NOTI, [])
        );
    }


    /**
     * Create a single toastr.
     *
     * @param string      $type
     * @param string      $message
     * @param bool|false $enableCustomButton
     *
     * @return string
     */


    public function toast(string $type, string $message = '', bool $enableCustomButton = false): string
    {
        $custombutton = $enableCustomButton ? $this->options() : '';

        return "new Toast({ message: '$message', type: '$type', $custombutton });";
    }


    /**
     * Shortcut for adding an error notification.
     *
     * @param string $message The notification's message
     * @param string $title   The notification's title
     * @param bool  $enableCustomButton
     *
     * @return NavToastr
     */


    public function error(string $message, bool $enableCustomButton = false): self
    {
        return $this->addNotification(self::ERROR, $message, $enableCustomButton);
    }


    /**
     * Shortcut for adding an info notification.
     *
     * @param string $message The notification's message
     * @param string $title   The notification's title
     * @param bool  $enableCustomButton
     *
     * @return NavToastr
     */

    public function info(string $message, bool $enableCustomButton = false): self
    {
        return $this->addNotification(self::INFO, $message, $enableCustomButton);
    }


    /**
     * Shortcut for adding an success notification.
     *
     * @param string $message The notification's message
     * @param string $title   The notification's title
     * @param bool  $enableCustomButton
     *
     * @return NavToastr
     */

    public function success(string $message, bool $enableCustomButton = false): self
    {
        return $this->addNotification(self::SUCCESS, $message, $enableCustomButton);
    }


    /**
     * Shortcut for adding an warning notification.
     *
     * @param string $message The notification's message
     * @param string $title   The notification's title
     * @param bool  $enableCustomButton
     *
     * @return NavToastr
     */


    public function warning(string $message, bool $enableCustomButton = false): self
    {
        return $this->addNotification(self::WARNING, $message, $enableCustomButton);
    }


     /**
     * Add a notification.
     *
     * @param string $type    Could be error, info, success, or warning.
     * @param string $message The notification's message
     * @param string $title   The notification's title
     * @param bool  $enableCustomButton
     *
     * @return NavToastr
     */


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



    /**
     * Get global nav-toastr custom buttons.
     *
     * @return string
     */


    public function options(): string
    {

        $option = array_map(
            function ($n) {
                if(array_key_exists('reload', $n) && $n['reload'] == true)
                    return array(
                        'text' => $n['text'],
                        'onClick' => 'function(){window.location.reload();}'
                    );
                return array(
                    'text' => $n['text'], 
                    'onClick' => 'function(){window.open("'.$n['url'].'");}'
                );
            },
            $this->config->get('nav-toastr.custombuttons', [])
        );

        $subject = json_encode($option);

        $search = ['"text"', '"onClick"', '\"', '\/\/', '\/', '\"', '}"', '"f'];

        $replace   = ['text', 'onClick', '"', '//', '/', '"', '}', 'f'];


        return 'customButtons : '.str_replace($search, $replace, $subject).'';
    }


    /**
     * Shortcut for custom redirect back.
     *
     *
     * @return NavToastr
     */


    public function back()
    {
        return back();
    }

    /**
     * Shortcut for custom redirect to url.
     *
     * @param string $url The url to redirect to
     *
     * @return NavToastr
     */


    public function to( string $url )
    {
        return redirect($url);
    }


    /**
     * Shortcut for custom redirect to named route.
     *
     * @param string $name The name route to redirect to
     *
     * @return NavToastr
     */


    public function named( string $name )
    {

        return redirect()->route($name);

    }


    /**
     * Shortcut for custom redirect to url outside your app.
     *
     * @param string $url The url to redirect to
     *
     * @return NavToastr
     */


    public function out( string $url )
    {

        return redirect()->away($url);

    }


    private function escapeSingleQuote(string $value): string
    {
        return str_replace("'", "\\'", $value);
    }


    /**
     * Clear all notifications.
     *
     * @return NavToastr
     */

    public function clear() {

        $this->notifications = [];

        return $this;

    }

}