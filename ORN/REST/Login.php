<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 2/22/15
 * Time: 1:47 AM
 */

namespace ORN\REST;

use ORN\Auth;

class Login implements RESTInterface
{

    /**
     * @param array $route
     * @param array $params
     * @return mixed
     */
    public function get($route, $params)
    {
        $auth = new Auth();
        $errors = array();
        $output = '';

        if ($auth->authenticate()) {
            if (isset($params['logout'])) {
                if ($auth->logout()) {
                    $output .= 'You have been logged out. Bye bye!';
                } else {
                    $errors[] = 'Error logging out.';
                }
            } else {
                $errors[] = 'You are already logged in.';
            }
        } else {
            $output .= 'Please log in.';
        }

        $html = true;
        if ($html) {
            foreach ($errors as $error) {
                $output .= '<br />' . $error . '<br />';
            }
            $loginHTML = @implode('', @file('template/login.html'));
            $output = str_replace('|content|', $output, $loginHTML);
            echo $output;
        } else {
            echo $output;
            foreach ($errors as $error) {
                echo $error;
            }
        }
    }

    /**
     * @param array $route
     * @param array $params
     * @param array $data
     * @return mixed
     */
    public function post($route, $params, $data)
    {
        global $config;

        $auth = new Auth();
        $login = $auth->authenticate();
        $errors = array();

        if (!$login) {
            if (isset($data['username']) && isset($data['password'])) {
                $username = $data['username'];
                $password = $data['password'];
                if ($username != '' && $password != '') {
                    $login = $auth->login($username, $password);
                } else {
                    $errors[] = 'Empty username or password!';
                }
            } else {
                $errors[] = 'Missing username or password!';
            }
        } else {
            $errors[] = 'You are already logged in.';
        }

        $output = '';
        if ($login) {
            $output .= "Hello {$config['user']['username']}! :)";
        } else {
            $errors[] = 'Login failed.';
            http_response_code(401);
        }

        $html = true;
        if ($html) {
            foreach ($errors as $error) {
                $output .= '<br />' . $error . '<br />';
            }
            $loginHTML = @implode('', @file('template/login.html'));
            $output = str_replace('|content|', $output, $loginHTML);
            echo $output;
        } else {
            echo $output;
            foreach ($errors as $error) {
                echo $error;
            }
        }
    }

    /**
     * @param array $route
     * @param array $params
     * @param array $data
     * @return mixed
     */
    public function put($route, $params, $data)
    {
        // TODO: Implement put() method.
    }

    /**
     * @param array $route
     * @param array $params
     * @return mixed
     */
    public function delete($route, $params)
    {
        // TODO: Implement delete() method.
    }
}
