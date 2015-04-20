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
use ORN\MediaFile;

/**
 * Class Posts
 *
 * TODO: add JSON variants
 *
 * @package ORN
 */
class Posts implements RESTInterface {
    /**
     * @param array $route
     * @param array $params
     * @param bool $isJSONRequest
     * @return mixed|void
     */
    public function get($route, $params, $isJSONRequest = false)
    {
        $postsRepo = new PostsRepo();
        $posts = $postsRepo->getPostsByTag(null);
        if (is_array($posts)) {
            header('Content-Type: application/json');
            echo json_encode($posts);
        } else {
            echo 'Error retrieving posts :(';
            http_response_code(503);
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
        $auth = new Auth();
        global $config;
        if ($auth->authenticate()) {
            $repo = new PostsRepo();
            $file = $this->handleFile();
            if (is_array($file)) {
                $data['media'] = $file['name'];
            }
            if (DEBUG && VERBOSE) var_dump('file name: ', $data['media']);
            $id = $repo->create($data);
            if ($id) {
                echo "Post added successfully. :)\n";
                if (is_array($file)) {
                    $fileTarget = $config['env']['app_root_dir'] .
                        '/media' . '/' . $file['type'] . '/' . $id . $file['name'];
                    if (DEBUG && VERBOSE) var_dump('target path: ', $fileTarget);
                    if (move_uploaded_file($file['tmp_name'], $fileTarget )) {
                        echo "File added successfully. :)\n";
                    }
                }
            } else {
                http_response_code(400);
            }
        } else {
            $this->denyAccess();
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
                echo "Post updated successfully. :)\n";
            } else {
                http_response_code(404);
                echo 'Post not found. :(';
            }
        } else {
            $this->denyAccess();
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
                http_response_code(404);
                echo 'Post not found. :(';
            }
        } else {
            $this->denyAccess();
        }
    }

    /**
     * HTTP status 403
     */
    private function denyAccess()
    {
        echo 'Access denied. You are not logged in.';
        http_response_code(403);
    }

    /**
     * Handle uploaded media file and move it to the desired destination
     *
     * @return null|string
     */
    private function handleFile()
    {
        global $config;
        $file = array_shift($_FILES);
        if (DEBUG) var_dump('file: ', $file);
        $maxUploadSize =
            (isset($config['env']) && isset($config['env']['max_upload_size']))
            ? $config['env']['max_upload_size']
            : 1000000
        ;
        if (DEBUG) var_dump('max file size: ', $maxUploadSize);
        if (DEBUG) var_dump('upload file size: ', $file['size']);
        if (DEBUG && VERBOSE) var_dump('file name and length: ', $file['name'], strlen($file['name']));
        if (is_array($file) && !$file['error'] &&
            $file['size'] < $maxUploadSize && // maximum file size
            strlen($file['name']) < 40        // maximum file name length
        ) {
            $fileType = '';
            if (DEBUG && VERBOSE) echo 'analyzing file...';
            if (MediaFile::imageFileType($file['tmp_name'], $file['name'])) {
                $fileType = 'image';
            } else if (MediaFile::audioFileType($file['tmp_name'], $file['name'])) {
                $fileType = 'audio';
            }
            if (DEBUG && VERBOSE) var_dump('file type: ', $fileType);
            if ($fileType) {
                $file['type'] = $fileType;
                return $file;
            }
        }

        return null;
    }

}
