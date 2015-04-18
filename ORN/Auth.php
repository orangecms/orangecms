<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 2/17/15
 * Time: 12:48 AM
 */

namespace ORN;
use Behat\Mink\Exception\Exception;


/**
 * Class Auth
 * @package ORN
 */
class Auth {
    /**
     * @var bool
     */
    private $testing = false;

    /**
     * @param bool $testing
     */
    public function __construct($testing = false) {
        if (!$testing) {
            session_start();
        } else {
            $this->testing = $testing;
        }
    }

    /**
     * @return bool
     */
    public function authenticate() {
        global $config;

        if (isset($_SESSION['username']) &&
            isset($config['user']['username'])
        ) {
            return (
                $config['user']['username'] == $_SESSION['username']
            );
        }
        return false;
    }

    /**
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function login($username, $password) {
        global $config;
        $sysUsername = $config['user']['username'];
        $sysPassword = $config['user']['password'];

        try {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            if ($sysUsername == $username &&
                password_verify($sysPassword, $passwordHash)
            ) {

                if (!isset($_SESSION['username'])) {
                    $_SESSION['username'] = $sysUsername;
                }

                return true;
            }
        } catch (Exception $e) {
            echo 'PHP Error - version below 4.x?';
        }

        return false;
    }

    /**
     * @return bool
     */
    public function logout() {
        unset($_SESSION['username']);
        if (!$this->testing) {
            session_destroy();
        }
        return(!isset($_SESSION['username']));
    }
}
