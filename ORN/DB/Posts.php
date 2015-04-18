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
    /**@var $table string */
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
    public function getPostsByTag($tag)
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
     * @return bool
     */
    public function create($data)
    {
        if (DEBUG) var_dump($data);

        if (isset($data['title'])) {
            // TODO: filter bad characters and stuff
            $title = $data['title'];
        }
        if (isset($data['text'])) {
            // TODO: filter bad characters and stuff
            $text = $data['text'];
        }
        if (isset($title) && isset($text)) {
            if (DEBUG) var_dump($title, $text);

            $data = array(
                'title' => $title,
                'text'  => $text
            );

            if (DEBUG && VERBOSE) var_dump($data);

            if ($this->getDb()->insert($this->table, $data)) {
                echo "Data inserted successfully! :)\n";
                return true;
            } else {
                echo "Error inserting data :(\n";
            }
        }
        return false;
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
