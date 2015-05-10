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
        $indexTemplate = @implode("", @file("template/index.html"));
        $postsTemplate = @implode("", @file("template/posts.html"));
        $postTemplate  = @implode("", @file("template/post.html"));

        global $config;
        date_default_timezone_set('Europe/Berlin');
        $tag = isset($route[0]) ? $route[0] : null;
        if (DEBUG) var_dump($tag);
        if (DEBUG && VERBOSE) var_dump($route);
        $posts = '';
        $postsRepo = new Posts();

        $metaTitle = '';
        $id = (int) $tag;
        if ($id) {
            $post = $postsRepo->getPostById($id);
            if (is_array($post)) {
                $posts .= $this->getPostHTML($post, $postTemplate);
                $metaTitle = $post['title'];
            }
        } else {
            foreach ($postsRepo->getPostsByTag($tag) as $post) {
                $posts .= $this->getPostHTML($post, $postTemplate);
            }
            $metaTitle = $config['blog']['title'];
        }
        $posts = str_replace('|posts|', $posts, $postsTemplate);

        $metaTitle = $metaTitle . ' - ' . $config['blog']['title'];
        $metaAuthor = $config['blog']['author'];
        $metaDescription = $config['blog']['description'];


        $output = str_replace('|meta_title|', $metaTitle, $indexTemplate);
        $output = str_replace('|meta_author|', $metaAuthor, $output);
        $output = str_replace('|meta_description|', $metaDescription, $output);

        $output = str_replace('|content|', $posts, $output);

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
        $appDir  = $config['env']['app_root_dir'];
        $baseURL = $config['env']['base_URL'];
        $imagePath = 'media/image/' . $id . $fileName;
        if ($type = MediaFile::imageFileType(
            $appDir . '/' . $imagePath, $fileName
        )) {
            return $baseURL . '/' . $imagePath;
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

    /**
     * @param $post
     * @param $postHTML
     * @return mixed
     */
    private function getPostHTML($post, $postHTML)
    {
        $id = $post['id'];
        $p = str_replace('|title|', $post['title'], $postHTML);
        $p = str_replace('|date|', $post['date'], $p);
        $p = str_replace('|id|', $id, $p);
        $p = str_replace('|text|', $this->parseText($post['text']), $p);
        $fileName = $this->getImagePath($id, $post['media']);
        $imageTag = $fileName ? "<img src=\"$fileName\" />" : $fileName;
        $p = str_replace('|image|', $imageTag, $p);
        return $p;
    }

    /**
     * TODO: recognize URLs and create links
     *
     * @param string $text
     * @return string
     */
    private function parseText($text)
    {
        return nl2br($text);
    }
}
