<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 4/4/15
 * Time: 2:50 AM
 */

namespace ORN\Output;

use ORN\DB\Posts;

/**
 * Class HTMLOutput
 * @package ORN
 */
class HTMLOutput implements OutputInterface {
    private $blog;

    /**
     * @param mixed $route
     * @param array $params
     * @return bool
     */
    function prepare($route, $params)
    {
        $indexHTML = @implode("", @file("template/index.html"));
        $postsHTML = @implode("", @file("template/posts.html"));
        $postHTML  = @implode("", @file("template/post.html"));

        date_default_timezone_set('Europe/Berlin');
        $tag = isset($route[0]) ? $route[0] : null;
        if (DEBUG) var_dump($tag);
        if (DEBUG && VERBOSE) var_dump($route);
        $posts = '';
        foreach ((new Posts())->getPostsByTag($tag) as $post) {
            $p = str_replace('|title|', $post['title'], $postHTML);
            $p = str_replace('|date|', $post['date'], $p);
            $p = str_replace('|id|', $post['id'], $p);
            $p = str_replace('|text|', $post['text'], $p);
            $p = str_replace('|image|', '<img src="media/image/orange.png" />', $p);
            $posts .= $p;
        }
        $postsHTML = str_replace('|posts|', $posts, $postsHTML);
        $output = str_replace('|content|', $postsHTML, $indexHTML);

        $this->blog = $output;
        return true;
    }

    /**
     * @return bool
     */
    function out()
    {
        echo $this->blog;
        return true;
    }
}
