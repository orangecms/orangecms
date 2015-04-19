<?php

namespace ORN\DB;

use Exception;
use mysqli;

/**
 * TODO: make more use of prepared statements and parametrize everything
 *
 * Class MySQL
 */
class MySQL {
    /**
     * @var string
     */
    private $host     = "";
    /**
     * @var string
     */
    private $database = "";
    /**
     * @var string
     */
    private $username = "";
    /**
     * @var string
     */
    private $password = "";

    /**
     * @param string $host
     * @param string $database
     * @param string $username
     * @param string $password
     */
    public function __construct($host = "", $database = "", $username = "", $password = "")
    {
        $this->host     = $host;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
    }

    //region CRUD operations
    /**
     * simple select query
     * TODO: allow selecting only specified columns
     *
     * @param string $table
     * @param array|string $where
     * @param array $order
     * @param bool $desc
     * @return array
     */
    public function select($table, $where = null, $order = null, $desc = false)
    {
        $link = $this->connect();
        if ($link instanceof mysqli) {
            list($whereString, $whereParams) = $this->getWhereString($where);
            /* prepare query */
            if ($order) {
                $order = ' ORDER BY ' . $order . ($desc ? ' DESC' : ' ASC');
            }
            $query = "SELECT * FROM {$table}{$whereString}{$order}";
            $statement = $link->prepare($query);
            /* execute */
            if (is_array($where) && count($whereString) > 0) {
                $this->bindParams($statement, $whereParams, null);
            }
            /* debug output */
            if (DEBUG) var_dump($query);
            if (DEBUG && VERBOSE) var_dump($link, $statement);
            /* execute */
            try {
                $result = $statement->execute();
                if ($result) {
                    /* fetch result to array */
                    $out = $this->fetchResult($statement);
                    $statement->close();
                    $link->close();
                    return $out;
                }
            } catch (Exception $e) {
                var_dump($e);
            }
            $statement->close();
        }
        $link->close();
        return null;
    }

    /**
     * @param string $table
     * @param array $data
     * @return bool
     */
    public function insert($table, $data)
    {
        $count = count($data);
        /* only insert if data is actually present */
        if (is_string($table) && is_array($data) && $count > 0) {
            $link = $this->connect();
            if ($link instanceof mysqli) {
                /* create as many '?'s as needed */
                $prepare = '?';
                for ($i = 1; $i < $count; $i++) {
                    $prepare .= ',?';
                }
                /* build string with columns (fields) */
                $tableFields = '';
                $first = true;
                foreach (array_keys($data) as $tableField) {
                    if ($first) {
                        $first = false;
                    } else {
                        $tableFields .= ',';
                    }
                    $tableFields .= $tableField;
                }
                /* prepare query */
                $params = array_values($data);
                $query = "INSERT INTO $table ($tableFields) VALUES ($prepare)";
                $statement = $link->prepare($query);
                /* debug output */
                if (DEBUG) var_dump($query);
                if (DEBUG && VERBOSE) var_dump($link, $statement);
                /* bind parameters and execute */
                $this->bindParams($statement, $params, null);
                $result = $statement->execute();
                $statement->close();
                $link->close();
                return $result;
            }
        }
        return false;
    }

    /**
     * @param string $table
     * @param array|string $where
     * @param array $data
     * @return bool
     */
    public function update($table, $where, $data)
    {
        $count = count($data);
        /* only update if data is actually present */
        if (is_string($table) && is_array($data) && $count > 0) {
            $link = $this->connect();
            if ($link instanceof mysqli) {
                list($whereString, $whereParams) = $this->getWhereString($where);
                /* build SET string */
                $updates = '';
                $first = true;
                foreach (array_keys($data) as $tableField) {
                    if ($first) {
                        $first = false;
                    } else {
                        $updates .= ',';
                    }
                    $updates .= $tableField . '=?';
                }
                /* prepare query */
                $dataParams = array_values($data);
                $query = "UPDATE $table SET {$updates}{$whereString}";
                $statement = $link->prepare($query);
                $result = false;
                /* debug output */
                if (DEBUG) var_dump($query);
                if (DEBUG && VERBOSE) var_dump($link, $statement);
                /* bind parameters of WHERE part */
                if (is_array($where) && count($whereString) > 0) {
                    /* bind parameters and execute */
                    $this->bindParams($statement, array_merge($dataParams, $whereParams), null);
                    $result = $statement->execute();
                }
                $statement->close();
                $link->close();
                return $result;
            }
        }
        return false;
    }

    /**
     * delete entries from a table where the given condition is satisfied
     *
     * @param string $table
     * @param array|string $where
     * @return bool
     */
    public function delete($table, $where)
    {
        $link = $this->connect();
        if ($link instanceof mysqli) {
            list($whereString, $whereParams) = $this->getWhereString($where);
            /* prepare query */
            $query = "DELETE FROM {$table}{$whereString}";
            $statement = $link->prepare($query);
            /* bind parameters */
            if (is_array($where) && count($whereString) > 0) {
                $this->bindParams($statement, $whereParams, null);
            }
            /* debug output */
            if (DEBUG) var_dump($query);
            if (DEBUG && VERBOSE) var_dump($link, $statement);
            /* execute */
            try {
                $result = $statement->execute();
                if ($result) {
                    $statement->close();
                    $link->close();
                    return true;
                }
            } catch (Exception $e) {
                var_dump($e);
            }
            $statement->close();
        }
        $link->close();
        return false;
    }

    //endregion
    //region table operations

    /**
     * @param string $table
     * @param array $fields
     * @return bool
     */
    public function create($table, $fields)
    {
        $link = $this->connect();
        if ($link instanceof mysqli) {
            /* prepare query */
            $tableFields = '';
            $first = true;
            foreach ($fields as $field => $definition) {
                if ($first) {
                    $first = false;
                } else {
                    $tableFields .= ',';
                }
                $tableFields .= $field . ' ' . $definition;
            }
            /* prepare query */
            $query = "CREATE TABLE IF NOT EXISTS $table ($tableFields)";
            $statement = $link->prepare($query);
            /* debug output */
            if (DEBUG) var_dump($query);
            if (DEBUG && VERBOSE) var_dump($link, $statement);
            /* execute and close */
            $result = $statement->execute();
            $statement->close();
            $link->close();
            /* return :) */
            return $result;
        }
        return false;
    }

    /**
     * @param $table
     * @return bool
     */
    public function drop($table)
    {
        $link = $this->connect();
        if ($link instanceof mysqli) {
            /* query the DB */
            $query = "DROP TABLE $table";
            $statement = $link->prepare($query);
            $result = $statement->execute();
            $statement->close();
            $link->close();
            /* return :) */
            return $result;
        }
        return false;
    }

    //endregion

    /**
     * Fetch the result for prepared statements
     *
     * @see http://stackoverflow.com/questions/1872064/mysqli-abstraction-fetching-arrays-from-prepared-statements
     *
     * @param \mysqli_stmt $statement
     * @return array
     */
    private function fetchResult(&$statement)
    {
        $meta = $statement->result_metadata();
        $params = array();
        $fields = array();
        while ($field = $meta->fetch_field()) {
            $params[] = &$fields[$field->name];
        }

        call_user_func_array(array($statement, 'bind_result'), $params);

        $result = array();
        while ($statement->fetch()) {
            $temp = array();
            foreach ($fields as $key => $val) {
                $temp[$key] = $val;
            }
            $result[] = $temp;
        }
        $meta->free();
        return $result;
    }

    /**
     * Bind parameters for prepared statements
     *
     * @see http://www.pontikis.net/blog/dynamically-bind_param-array-mysqli
     *
     * @param \mysqli_stmt $statement
     * @param array $values
     * @param string $types Types: s = string, i = integer, d = double, b = blob
     */
    private function bindParams(&$statement, $values, $types)
    {
        $count = count($values);
        /* if no types are provided, default to all strings */
        if ($types == null) {
            $types = '';
            for ($i = 0; $i < $count; $i++) {
                $types .= 's';
            }
        }
        /* build params array */
        $params = array();
        /* with call_user_func_array, array params must be passed by reference */
        $params[] = &$types;
        for ($i = 0; $i < $count; $i++) {
            $params[] = &$values[$i];
        }
        call_user_func_array(array($statement, 'bind_param'), $params);
    }

    /**
     * build a WHERE clause
     *
     * @param $where
     * @return array
     */
    private function getWhereString($where)
    {
        $whereString = '';
        $whereParams = array();
        if (is_array($where)) {
            $whereString = ' WHERE ';
            $first = true;
            foreach ($where as $column => $value) {
                if ($first) {
                    $first = false;
                } else {
                    $whereString .= ' AND ';
                }
                $whereString .= $column . '=?';
                $whereParams[] = $value;
            }
            return array($whereString, $whereParams);
        } else if (is_string($where)) {
            /* TODO: parametrize statement for WHERE ... IN () */
            $whereString = ' WHERE ' . $where;
            return array($whereString, $whereParams);
        }
        return array($whereString, $whereParams);
    }

    /**
     * Connect to the DBMS and select the DB
     *
     * @return resource
     */
    private function connect()
    {
        $link = new mysqli($this->host, $this->username, $this->password);
        if (!$link->connect_errno && $link->set_charset("utf8")) {
            mysqli_select_db($link, $this->database);
            return $link;
        } else {
            return null;
        }
    }
}
