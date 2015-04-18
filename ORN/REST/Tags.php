<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 4/4/15
 * Time: 2:48 AM
 */

namespace ORN\REST;

use ORN\DB\DbRepository;

/**
 * Class Tags
 * @package ORN
 */
class Tags implements RESTInterface {
    /**
     * @param array $route
     * @param array $params
     * @return mixed
     */
    public function get($route, $params)
    {
        echo ":)";
    }

    /**
     * @param array $route
     * @param array $params
     * @param array $data
     * @return mixed
     */
    public function post($route, $params, $data)
    {
        // TODO: Implement post() method.
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
     * save data to database
     *
     * @param $data
     * @return bool
     */
    protected function create($data)
    {
        // TODO: Implement saveToDb() method.
    }
}
