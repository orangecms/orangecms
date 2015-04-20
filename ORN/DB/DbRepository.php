<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 4/9/15
 * Time: 7:10 PM
 */

namespace ORN\DB;

/**
 * Class DbRepository
 * @package ORN\DB
 */
abstract class DbRepository {
    /** @var $db MySQL */
    private $db = null;

    /** @var $table string */
    private $table = '';

    /**
     * get an instance of the DB interface
     *
     * @return MySQL
     */
    public function getDb() {
        global $config;
        if ($this->db == null) {
            $this->db = new MySQL(
                $config['sql']['host'],
                $config['sql']['database'],
                $config['sql']['username'],
                $config['sql']['password']
            );
        }
        return $this->db;
    }

    /**
     * @param string $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * save data to database
     *
     * @param array $data
     * @return bool
     */
    abstract protected function create($data);

    /**
     * update data in database
     *
     * @param array|string $where
     * @param array $data
     * @return bool
     */
    abstract public function update($where, $data);

    /**
     * delete data from database by default identifier
     *
     * @param array|string $where
     * @return bool
     */
    public function delete($where)
    {
        if (DEBUG && VERBOSE) var_dump($this->table, $where);
        if ($this->table != '') {
            return $this->getDb()->delete($this->table, $where);
        } else {
            return false;
        }
    }
}
