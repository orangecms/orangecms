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
class Tags extends DbRepository
{
    /** @var string */
    private $table = 'tags';

    /**
     * constructor
     */
    public function __construct()
    {
        $this->setTable($this->table);
    }

    /**
     * @param $postId
     * @return array
     */
    public function getTagsByPostId($postId = null)
    {
        if ($postId) {
            /* TODO: prepared statement for inner SELECT! */
            $tags = $this->getDb()->select(
                $this->table,
                array('post_id' => $postId)
            );
            return $tags;
        } else {
            return array();
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    public function create($data)
    {
        if (DEBUG) var_dump($data);

        if (isset($data['post_id'])) {
            $postId = $data['post_id'];
        }
        if (isset($data['tag'])) {
            // TODO: filter bad characters and stuff
            $tag = $data['tag'];
        }
        if (isset($postId) && isset($tag)) {
            if (DEBUG) var_dump($postId, $tag);

            $data = array(
                'post_id' => $postId,
                'tag'     => $tag
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
