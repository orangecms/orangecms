<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 4/4/15
 * Time: 2:50 AM
 */

namespace ORN\Output;

use ORN\DB\Posts;
use ORN\MediaFile;

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
        $postsRepo = new Posts();
        foreach ($postsRepo->getPostsByTag($tag) as $post) {
            $id = $post['id'];
            $fileName = $this->getImagePath($id, $post['media']);
            $imageTag = $fileName ? "<img src=\"$fileName\" />" : $fileName;

            $p = str_replace('|title|', $post['title'], $postHTML);
            $p = str_replace('|date|', $post['date'], $p);
            $p = str_replace('|id|', $id, $p);
            $p = str_replace('|text|', $post['text'], $p);
            $p = str_replace('|image|', $imageTag, $p);
            $posts .= $p;
        }
        $postsHTML = str_replace('|posts|', $posts, $postsHTML);

        $output = str_replace('|content|', $postsHTML, $indexHTML);
        global $config;
        $baseURL = $config['env']['base_URL'];
        $output = str_replace('|base_URL|', $baseURL, $output);

        $this->blog = $output;
        return true;
    }

    /**
     * @param int $id
     * @param string $fileName
     * @return null|string
     */
    private function getImagePath($id, $fileName)
    {
        global $config;
        $appDir = $config['env']['app_root_dir'];
        $imagePath = 'media/image/' . $id . $fileName;
        if ($type = MediaFile::imageFileType(
            $appDir . '/' . $imagePath, $fileName
        )) {
            return $imagePath;
        }
        return '';
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
