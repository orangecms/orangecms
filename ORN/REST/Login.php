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
     * @param bool $isJSONRequest
     * @return mixed
     */
    public function get($route, $params, $isJSONRequest = false)
    {
        $auth = new Auth();
        $errors = array();
        $output = '';
        /* authentication */
        $login = $auth->authenticate();
        if ($login) {
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
        /* output */
        $this->output($isJSONRequest, $login, $output, $errors);
    }

    /**
     * @param array $route
     * @param array $params
     * @param array $data
     * @param bool $isJSONRequest
     * @return mixed
     */
    public function post($route, $params, $data, $isJSONRequest = false)
    {
        global $config;

        $auth = new Auth();
        $login = $auth->authenticate();
        $info = '';
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

        if ($login) {
            $info .= "Hello {$config['user']['username']}! :)";
        } else {
            $errors[] = 'Login failed.';
            header('WWW-Authenticate: xBasic realm=""');
            http_response_code(401);
        }
        /* output */
        $this->output($isJSONRequest, $login, $info, $errors);
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

    /**
     * @param bool $isJsonRequest
     * @param bool $login
     * @param string $info
     * @param array $errors
     */
    private function output($isJsonRequest, $login, $info, $errors)
    {
        /* output */
        if ($isJsonRequest) {
            header('Content-Type: application/json');
            echo json_encode(
                array(
                    'info'   => $info,
                    'errors' => $errors
                )
            );
        } else {
            /* load HTML template */
            $loginHTML = @implode('', @file('template/login.html'));
            /* append errors */
            if ($login) {
                $info .= @implode('', @file('template/links.html'));
            }
            $info .= '<br />' . implode('<br />', $errors);
            /* add output to HTML and echo */
            echo str_replace('|content|', $info, $loginHTML);
        }
    }
}
