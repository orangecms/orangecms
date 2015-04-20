<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 4/4/15
 * Time: 2:48 AM
 */

namespace ORN\REST;

use ORN\DB\Tags as TagsRepo;

/**
 * Class Tags
 * @package ORN
 */
class Tags implements RESTInterface {
    /**
     * @param array $route
     * @param array $params
     * @param bool $isJSONRequest
     * @return mixed
     */
    public function get($route, $params, $isJSONRequest = false)
    {
        if (isset($route[0])) {
            $id = (int) $route[0];
            $tagsRepo = new TagsRepo();
            $posts = $tagsRepo->getTagsByPostId($id);
            if (is_array($posts)) {
                header('Content-Type: application/json');
                echo json_encode($posts);
            } else {
                echo 'Error retrieving posts :(';
                http_response_code(503);
            }
        }
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
