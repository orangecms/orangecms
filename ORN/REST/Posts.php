<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 4/3/15
 * Time: 10:34 PM
 */

namespace ORN\REST;

use ORN\Auth;
use ORN\DB\Posts as PostsRepo;
use ORN\Output\RSSOutput;

/**
 * Class Posts
 * @package ORN
 */
class Posts implements RESTInterface {
    /**
     * @param array $route
     * @param array $params
     * @return mixed|void
     */
    public function get($route, $params)
    {
        $rss = new RSSOutput();
        if ($rss->prepare($route, $params)) {
            $rss->out();
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
        $auth = new Auth();
        if ($auth->authenticate()) {
            $repo = new PostsRepo();
            if ($repo->create($data)) {
                echo "Post added successfully. :)\n";
            } else {
                http_response_code(503);
            }
        } else {
            echo "Access denied. You are not logged in.";
            http_response_code(403);
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
        $auth = new Auth();
        if ($auth->authenticate()) {
            $id = $route[0];
            $repo = new PostsRepo();
            if ($id &&
                $repo->update(array('id' => $id), $data)) {
                echo "Post added successfully. :)\n";
            } else {
                http_response_code(503);
            }
        } else {
            echo "Access denied. You are not logged in.";
            http_response_code(403);
        }
    }

    /**
     * @param array $route
     * @param array $params
     * @return mixed
     */
    public function delete($route, $params)
    {
        $auth = new Auth();
        if ($auth->authenticate()) {
            $id = (int) $route[0];
            $repo = new PostsRepo();
            if ($repo->delete(array('id' => $id))) {
                echo 'Post deleted successfully. :)';
            } else {
                echo 'Post not found. :(';
            }
        } else {
            echo "Access denied. You are not logged in.";
            http_response_code(403);
        }
    }

}
