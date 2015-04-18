<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 2/16/15
 * Time: 9:51 PM
 */

require_once('../autoload.php');
require_once('../password.php');
require_once('../config.php');
require_once('tables.php');

$auth = new ORN\Auth();
if ($auth->authenticate()) {

    $bDropTables      = true;
    $bCreateDummyData = true;

    global $config;
    /* database */
    $db = new ORN\DB\MySQL(
        $config['sql']['host'],
        $config['sql']['database'],
        $config['sql']['username'],
        $config['sql']['password']
    );

    if ($bDropTables) {
        $tables = array('tags', 'posts');
        foreach ($tables as $table) {
            echo "\nDropping table '$table'...\n";
            $db->drop($table);
        }
    }

    /*********/
    /* posts */
    /*********/
    $table = 'posts';
    echo "\nCreating table '$table'...\n";
    if ($db->create($table, $SQL_TABLES[$table])) {
        echo "Table '$table' created successfully (or existed already) :)\n";
        /* dummy data */
        if ($bCreateDummyData) {
            $lipsumApi = 'http://www.lipsum.com/feed/xml';
            $data = array(
                array(
                    'title' => 'test entry 1',
                    'text'  => simplexml_load_file($lipsumApi . '?amount=512&what=bytes')->lipsum
                ),
                array(
                    'title' => 'test entry 2',
                    'text'  => simplexml_load_file($lipsumApi . '?amount=2048&what=bytes')->lipsum
                ),
                array(
                    'title' => 'test entry 3',
                    'text'  => simplexml_load_file($lipsumApi . '?amount=1024&what=bytes')->lipsum
                ),
            );
            /* insert into DB */
            foreach ($data as $row) {
                if ($db->insert($table, $row)) {
                    echo "Dummy data inserted successfully! :)\n";
                } else {
                    echo "Error inserting dummy data :(\n";
                }
            }
        }
    } else {
        echo "Error creating table '$table' :(\n";
    }

    /********/
    /* tags */
    /********/
    $table = 'tags';
    echo "\nCreating table '$table'...\n";
    if ($db->create($table, $SQL_TABLES[$table])) {
        echo "Table '$table' created successfully (or existed already) :)\n";
        /* dummy data */
        if ($bCreateDummyData) {
            $data = array(
                array(
                    'post_id' => 1,
                    'tag'     => 'cats',
                ),
                array(
                    'post_id' => 1,
                    'tag'     => 'tech',
                ),
                array(
                    'post_id' => 2,
                    'tag'     => 'tech',
                ),
            );
            /* insert into DB */
            foreach ($data as $row) {
                if ($db->insert($table, $row)) {
                    echo "Dummy data inserted successfully! :)\n";
                } else {
                    echo "Error inserting dummy data :(\n";
                }
            }
        }
    } else {
        echo "Error creating table '$table' :(\n";
    }

} else {
    echo "Access denied. You are not logged in.";
    http_response_code(403);
}
