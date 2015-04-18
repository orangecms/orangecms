<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 4/3/15
 * Time: 10:28 PM
 */

namespace ORN\REST;

/**
 * Interface RestApi
 * @package ORN
 */
interface RESTInterface {
    /**
     * @param array $route
     * @param array $params
     * @return mixed
     */
    public function get($route, $params);

    /**
     * @param array $route
     * @param array $params
     * @param array $data
     * @return mixed
     */
    public function post($route, $params, $data);

    /**
     * @param array $route
     * @param array $params
     * @param array $data
     * @return mixed
     */
    public function put($route, $params, $data);

    /**
     * @param array $route
     * @param array $params
     * @return mixed
     */
    public function delete($route, $params);
}
