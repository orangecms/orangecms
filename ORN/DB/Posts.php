<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 4/3/15
 * Time: 10:34 PM
 */

namespace ORN\DB;

/**
 * Class Posts
 * @package ORN
 */
class Posts extends DbRepository
{
    /** @var $table string */
    private $table = 'posts';

    /**
     * constructor
     */
    public function __construct()
    {
        $this->setTable($this->table);
    }

    /**
     * @param $tag
     * @return array
     */
    public function getPostsByTag($tag = null)
    {
        if ($tag) {
            /* TODO: prepared statement for inner SELECT! */
            $posts = $this->getDb()->select(
                $this->table,
                "id IN (SELECT post_id FROM tags WHERE tag='$tag')",
                'date',
                true
            );
            return $posts;
        } else {
            $posts = $this->getDb()->select(
                $this->table,
                null,
                'date',
                true
            );
            return $posts;
        }
    }

    /**
     * @param $data
     * @return int
     */
    public function create($data)
    {
        if (DEBUG) var_dump('data for DB: ', $data);

        // TODO: filter bad characters and stuff
        $title = isset($data['title']) ? $data['title'] : null;
        $text  = isset($data['text'])  ? $data['text']  : null;
        $media = isset($data['media']) ? $data['media'] : null;

        if (DEBUG && VERBOSE) var_dump('title and text: ', $title, $text);

        if ($title && $text) {
            $result = $this->getDb()->insert(
                $this->table,
                array(
                    'title' => $title,
                    'text'  => $text,
                    'media' => $media
                )
            );
            if ($result) {
                echo "Data inserted successfully! :)\n";
                return $result;
            } else {
                echo "Error inserting data :(\n";
            }
        }
        return 0;
    }

    /**
     * @param array|string $where
     * @param array $data
     * @return bool
     */
    public function update($where, $data)
    {
        return $this->getDb()->update($this->table, $where, $data);
    }
}
